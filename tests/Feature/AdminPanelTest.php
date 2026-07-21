<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Card;
use App\Models\CardAppointment;
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
}
