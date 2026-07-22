<?php

namespace Tests\Feature;

use App\Filament\Resources\CardAppointmentResource;
use App\Filament\Resources\CardResource;
use App\Filament\Resources\ContactMessageResource;
use App\Models\AuditLog;
use App\Models\Card;
use App\Models\CardAppointment;
use App\Models\CardLink;
use App\Models\CardPhoto;
use App\Models\CardSchedule;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function makeUserWithCard(): User
    {
        $user = User::factory()->create();
        $user->card()->create([
            'slug'         => 'titular-' . $user->id,
            'display_name' => 'Titular Teste',
            'is_active'    => true,
        ]);
        return $user->fresh(['card']);
    }

    // ── acesso ao painel ─────────────────────────────────────────────────────

    public function test_usuario_comum_nao_acessa_o_painel_admin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_acessa_o_painel(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->get('/admin')->assertSuccessful();
    }

    // ── recursos existentes ───────────────────────────────────────────────────

    public function test_admin_acessa_lista_de_usuarios(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->get('/admin/users')->assertSuccessful();
    }

    public function test_admin_acessa_lista_de_cartoes(): void
    {
        $admin = $this->makeAdmin();
        $this->makeUserWithCard();

        $this->actingAs($admin)->get('/admin/cards')->assertSuccessful();
    }

    public function test_admin_acessa_pagina_de_configuracoes(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->get('/admin/configuracoes')->assertSuccessful();
    }

    // ── novos recursos ────────────────────────────────────────────────────────

    public function test_admin_acessa_lista_de_mensagens_de_contato(): void
    {
        $admin = $this->makeAdmin();
        $titular = $this->makeUserWithCard();
        ContactMessage::create([
            'card_id'      => $titular->card->id,
            'sender_name'  => 'Visitante',
            'sender_email' => 'visitante@teste.com',
            'message'      => 'Olá, gostaria de saber mais.',
        ]);

        $this->actingAs($admin)->get('/admin/contact-messages')->assertSuccessful();
    }

    public function test_admin_acessa_lista_de_agendamentos(): void
    {
        $admin = $this->makeAdmin();
        $titular = $this->makeUserWithCard();
        $schedule = CardSchedule::create([
            'card_id'       => $titular->card->id,
            'service_name'  => 'Consulta',
            'slot_duration' => 30,
            'is_active'     => true,
        ]);
        CardAppointment::create([
            'card_schedule_id' => $schedule->id,
            'visitor_name'     => 'Cliente Teste',
            'visitor_email'    => 'cliente@teste.com',
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '10:00',
            'status'           => 'pending',
            'token'            => 'token-teste',
        ]);

        $this->actingAs($admin)->get('/admin/card-appointments')->assertSuccessful();
    }

    public function test_admin_acessa_log_de_auditoria(): void
    {
        $admin = $this->makeAdmin();
        $titular = $this->makeUserWithCard();
        AuditLog::create([
            'action'     => 'impersonation',
            'admin_id'   => $admin->id,
            'target_id'  => $titular->id,
            'ip_address' => '127.0.0.1',
        ]);

        $this->actingAs($admin)->get('/admin/audit-logs')->assertSuccessful();
    }

    public function test_audit_log_nao_permite_criacao_manual(): void
    {
        $admin = $this->makeAdmin();

        $this->assertFalse(\App\Filament\Resources\AuditLogResource::canCreate());
    }

    // ── impersonação ──────────────────────────────────────────────────────────

    public function test_admin_pode_impersonar_e_voltar(): void
    {
        $admin = $this->makeAdmin();
        $titular = $this->makeUserWithCard();

        $this->actingAs($admin);
        $this->assertAuthenticatedAs($admin);

        // Simula a ação de impersonar (mesma lógica da Action no UserResource)
        session(['impersonator_id' => $admin->id]);
        auth()->loginUsingId($titular->id);
        $this->assertAuthenticatedAs($titular);

        $response = $this->post('/impersonate/stop');
        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
        $this->assertNull(session('impersonator_id'));
    }

    public function test_impersonate_stop_sem_sessao_retorna_403(): void
    {
        $titular = $this->makeUserWithCard();

        $this->actingAs($titular)->post('/impersonate/stop')->assertForbidden();
    }

    // ── relation managers do cartão ─────────────────────────────────────────────

    public function test_admin_acessa_tela_de_edicao_do_cartao_com_relation_managers(): void
    {
        $admin = $this->makeAdmin();
        $titular = $this->makeUserWithCard();
        CardLink::create([
            'card_id' => $titular->card->id,
            'label'   => 'Instagram',
            'url'     => 'https://instagram.com/teste',
            'order'   => 0,
        ]);
        CardPhoto::create([
            'card_id' => $titular->card->id,
            'path'    => 'photos/teste.jpg',
            'order'   => 0,
        ]);

        $this->actingAs($admin)
            ->get("/admin/cards/{$titular->card->id}/edit")
            ->assertSuccessful();
    }

    // ── badges de navegação ──────────────────────────────────────────────────────

    public function test_badge_de_mensagens_reflete_apenas_nao_lidas(): void
    {
        $titular = $this->makeUserWithCard();
        ContactMessage::create([
            'card_id'      => $titular->card->id,
            'sender_name'  => 'Visitante 1',
            'sender_email' => 'v1@teste.com',
            'message'      => 'Mensagem não lida',
        ]);
        ContactMessage::create([
            'card_id'      => $titular->card->id,
            'sender_name'  => 'Visitante 2',
            'sender_email' => 'v2@teste.com',
            'message'      => 'Mensagem lida',
            'read_at'      => now(),
        ]);

        $this->assertSame('1', ContactMessageResource::getNavigationBadge());
    }

    public function test_badge_de_mensagens_nulo_quando_tudo_lido(): void
    {
        $this->assertNull(ContactMessageResource::getNavigationBadge());
    }

    public function test_badge_de_agendamentos_reflete_apenas_pendentes(): void
    {
        $titular = $this->makeUserWithCard();
        $schedule = CardSchedule::create([
            'card_id'       => $titular->card->id,
            'service_name'  => 'Consulta',
            'slot_duration' => 30,
            'is_active'     => true,
        ]);
        CardAppointment::create([
            'card_schedule_id' => $schedule->id,
            'visitor_name'     => 'Pendente',
            'visitor_email'    => 'pendente@teste.com',
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '10:00',
            'status'           => 'pending',
            'token'            => 'token-1',
        ]);
        CardAppointment::create([
            'card_schedule_id' => $schedule->id,
            'visitor_name'     => 'Confirmado',
            'visitor_email'    => 'confirmado@teste.com',
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '11:00',
            'status'           => 'confirmed',
            'token'            => 'token-2',
        ]);

        $this->assertSame('1', CardAppointmentResource::getNavigationBadge());
    }

    public function test_badge_de_agendamentos_nulo_sem_pendentes(): void
    {
        $this->assertNull(CardAppointmentResource::getNavigationBadge());
    }

    public function test_card_resource_expoe_relation_managers_de_links_fotos_e_servicos(): void
    {
        $relations = CardResource::getRelations();

        $this->assertContains(\App\Filament\Resources\CardResource\RelationManagers\LinksRelationManager::class, $relations);
        $this->assertContains(\App\Filament\Resources\CardResource\RelationManagers\PhotosRelationManager::class, $relations);
        $this->assertContains(\App\Filament\Resources\CardResource\RelationManagers\ServicesRelationManager::class, $relations);
    }
}
