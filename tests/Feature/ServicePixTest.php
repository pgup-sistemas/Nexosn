<?php

namespace Tests\Feature;

use App\Livewire\Card\ServiceManager;
use App\Models\Card;
use App\Models\CardService;
use App\Models\User;
use App\Services\PlanService;
use App\Services\QrCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ServicePixTest extends TestCase
{
    use RefreshDatabase;

    // ── helpers ─────────────────────────────────────────────────────────────────

    private function makeUser(string $plan = 'free'): User
    {
        $user = User::factory()->create(['plan' => $plan]);
        $user->card()->create([
            'slug'         => 'teste-' . $user->id,
            'display_name' => 'Titular Teste',
            'is_active'    => true,
            'pix_key'      => 'chave@pix.com',
        ]);
        return $user->fresh(['card']);
    }

    private function makeService(Card $card, array $attrs = []): CardService
    {
        return $card->services()->create(array_merge([
            'name'       => 'Serviço Teste',
            'price'      => 50.00,
            'lucide_icon'=> 'tag',
            'is_active'  => true,
            'sort_order' => 0,
        ], $attrs));
    }

    // ── PlanService limits ────────────────────────────────────────────────────

    public function test_free_permite_ate_3_servicos(): void
    {
        $ps   = app(PlanService::class);
        $user = User::factory()->create(['plan' => 'free']);

        $this->assertTrue($ps->withinLimit($user, 'services', 0));
        $this->assertTrue($ps->withinLimit($user, 'services', 2));
        $this->assertFalse($ps->withinLimit($user, 'services', 3));
    }

    public function test_pro_permite_servicos_ilimitados(): void
    {
        $ps   = app(PlanService::class);
        $user = User::factory()->create(['plan' => 'pro']);

        $this->assertTrue($ps->withinLimit($user, 'services', 100));
    }

    // ── QrCodeService PIX payload ─────────────────────────────────────────────

    public function test_payload_pix_comeca_com_emv_id_e_termina_com_crc16(): void
    {
        $qr      = app(QrCodeService::class);
        $payload = $qr->pixPayload('chave@pix.com', 99.90, 'Titular Teste', 'Porto Velho', 'SRV1');

        // Deve começar com campo 00 (Payload Format Indicator) e terminar com 4 chars de CRC
        $this->assertStringStartsWith('000201', $payload);
        $this->assertMatchesRegularExpression('/6304[0-9A-F]{4}$/', $payload);
    }

    public function test_payload_pix_contem_chave_e_valor(): void
    {
        $qr      = app(QrCodeService::class);
        $payload = $qr->pixPayload('chave@pix.com', 50.00, 'Teste', 'Brasil', '***');

        $this->assertStringContainsString('chave@pix.com', $payload);
        $this->assertStringContainsString('50.00', $payload);
    }

    public function test_payload_pix_valor_formatado_corretamente(): void
    {
        $qr = app(QrCodeService::class);

        $payload = $qr->pixPayload('11999999999', 1.5, 'Teste', 'Brasil');
        $this->assertStringContainsString('1.50', $payload);

        $payload2 = $qr->pixPayload('11999999999', 1000.0, 'Teste', 'Brasil');
        $this->assertStringContainsString('1000.00', $payload2);
    }

    public function test_svg_retorna_conteudo_svg_valido(): void
    {
        $qr  = app(QrCodeService::class);
        $svg = $qr->svg('https://exemplo.com');

        $this->assertStringContainsString('<svg', $svg);
    }

    // ── ServicePixController — endpoint JSON ──────────────────────────────────

    public function test_payload_endpoint_retorna_json_com_qr_e_payload(): void
    {
        $user    = $this->makeUser();
        $card    = $user->card;
        $service = $this->makeService($card);

        $response = $this->getJson("/u/{$card->slug}/servico/{$service->id}/payload");

        $response->assertOk()
            ->assertJsonStructure(['payload', 'qr_svg', 'formatted', 'name'])
            ->assertJsonFragment(['name' => 'Serviço Teste', 'formatted' => 'R$ 50,00']);
    }

    public function test_payload_endpoint_retorna_404_se_servico_inativo(): void
    {
        $user    = $this->makeUser();
        $service = $this->makeService($user->card, ['is_active' => false]);

        $this->getJson("/u/{$user->card->slug}/servico/{$service->id}/payload")
            ->assertNotFound();
    }

    public function test_payload_endpoint_retorna_404_se_cartao_inativo(): void
    {
        $user = $this->makeUser();
        $user->card->update(['is_active' => false]);
        $service = $this->makeService($user->card);

        $this->getJson("/u/{$user->card->slug}/servico/{$service->id}/payload")
            ->assertNotFound();
    }

    public function test_payload_endpoint_retorna_422_sem_chave_pix(): void
    {
        $user = $this->makeUser();
        $user->card->update(['pix_key' => null]);
        $service = $this->makeService($user->card);

        $this->getJson("/u/{$user->card->slug}/servico/{$service->id}/payload")
            ->assertStatus(422);
    }

    public function test_payload_endpoint_rejeita_servico_de_outro_cartao(): void
    {
        $user1    = $this->makeUser();
        $user2    = $this->makeUser();
        $service  = $this->makeService($user2->card);

        $this->getJson("/u/{$user1->card->slug}/servico/{$service->id}/payload")
            ->assertNotFound();
    }

    // ── ServicePixController — link direto ────────────────────────────────────

    public function test_link_direto_exibe_cartao_publico(): void
    {
        $user    = $this->makeUser();
        $service = $this->makeService($user->card);

        $this->get("/u/{$user->card->slug}/pagar/{$service->id}")
            ->assertOk()
            ->assertViewIs('card.show')
            ->assertViewHas('autoOpenService', $service->id);
    }

    // ── Livewire ServiceManager ───────────────────────────────────────────────

    public function test_usuario_pode_criar_servico(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        Livewire::test(ServiceManager::class, ['card' => $user->card])
            ->call('startCreate')
            ->set('name', 'Corte de cabelo')
            ->set('price', '45.00')
            ->set('lucide_icon', 'scissors')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('card_services', [
            'card_id' => $user->card->id,
            'name'    => 'Corte de cabelo',
            'price'   => 45.00,
        ]);
    }

    public function test_free_bloqueado_ao_criar_4o_servico(): void
    {
        $user = $this->makeUser('free');
        $this->actingAs($user);

        $this->makeService($user->card, ['name' => 'S1']);
        $this->makeService($user->card, ['name' => 'S2']);
        $this->makeService($user->card, ['name' => 'S3']);

        Livewire::test(ServiceManager::class, ['card' => $user->card->fresh()])
            ->call('startCreate')
            ->assertSet('showForm', false);

        $this->assertDatabaseCount('card_services', 3);
    }

    public function test_pro_pode_criar_mais_de_3_servicos(): void
    {
        $user = $this->makeUser('pro');
        $this->actingAs($user);

        $this->makeService($user->card, ['name' => 'S1']);
        $this->makeService($user->card, ['name' => 'S2']);
        $this->makeService($user->card, ['name' => 'S3']);

        Livewire::test(ServiceManager::class, ['card' => $user->card->fresh()])
            ->call('startCreate')
            ->set('name', 'S4')
            ->set('price', '10.00')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('card_services', 4);
    }

    public function test_usuario_pode_editar_servico_mesmo_no_limite_free(): void
    {
        $user = $this->makeUser('free');
        $this->actingAs($user);

        $this->makeService($user->card, ['name' => 'S1']);
        $this->makeService($user->card, ['name' => 'S2']);
        $svc = $this->makeService($user->card, ['name' => 'S3']);

        Livewire::test(ServiceManager::class, ['card' => $user->card->fresh()])
            ->call('startEdit', $svc->id)
            ->set('name', 'S3 Editado')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('card_services', ['id' => $svc->id, 'name' => 'S3 Editado']);
    }

    public function test_usuario_pode_deletar_servico(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);
        $svc  = $this->makeService($user->card);

        Livewire::test(ServiceManager::class, ['card' => $user->card])
            ->call('delete', $svc->id);

        $this->assertDatabaseMissing('card_services', ['id' => $svc->id]);
    }

    public function test_toggle_active_inverte_estado(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);
        $svc  = $this->makeService($user->card, ['is_active' => true]);

        Livewire::test(ServiceManager::class, ['card' => $user->card])
            ->call('toggleActive', $svc->id);

        $this->assertDatabaseHas('card_services', ['id' => $svc->id, 'is_active' => false]);
    }

    public function test_validacao_nome_obrigatorio(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        Livewire::test(ServiceManager::class, ['card' => $user->card])
            ->call('startCreate')
            ->set('price', '10.00')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_validacao_preco_invalido(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        Livewire::test(ServiceManager::class, ['card' => $user->card])
            ->call('startCreate')
            ->set('name', 'Servico')
            ->set('price', 'abc')
            ->call('save')
            ->assertHasErrors(['price']);
    }

    public function test_servico_nao_aparece_no_cartao_se_sem_pix_key(): void
    {
        $user = $this->makeUser();
        $user->card->update(['pix_key' => null]);
        $this->makeService($user->card);

        $response = $this->get("/u/{$user->card->slug}");
        // Verifica que o bloco da seção não foi renderizado (ícone receipt só aparece na seção Serviços)
        $response->assertOk()->assertDontSee('data-lucide="receipt"', false);
    }

    public function test_servicos_aparecem_no_cartao_publico(): void
    {
        $user = $this->makeUser();
        $this->makeService($user->card, ['name' => 'Consultoria Jurídica']);

        $response = $this->get("/u/{$user->card->slug}");
        $response->assertOk()->assertSee('Consultoria Jurídica');
    }

    // ── CardService model ─────────────────────────────────────────────────────

    public function test_formatted_price_em_reais(): void
    {
        $user = $this->makeUser();
        $svc  = $this->makeService($user->card, ['price' => 149.90]);

        $this->assertSame('R$ 149,90', $svc->formatted_price);
    }

    public function test_servico_deletado_em_cascata_ao_deletar_cartao(): void
    {
        $user = $this->makeUser();
        $svc  = $this->makeService($user->card);

        $user->card->delete();

        $this->assertDatabaseMissing('card_services', ['id' => $svc->id]);
    }
}
