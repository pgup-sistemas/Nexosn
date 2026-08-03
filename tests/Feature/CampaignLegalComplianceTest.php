<?php

namespace Tests\Feature;

use App\Livewire\Campaign\ProfileEditor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CampaignLegalComplianceTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithCard(string $template = 'campaign-hero'): User
    {
        $user = User::factory()->create([
            'plan' => 'pro',
            'plan_expires_at' => now()->addMonth(),
        ]);
        $user->card()->create([
            'slug' => 'titular-' . $user->id,
            'display_name' => 'Titular Teste',
            'is_active' => true,
            'template' => $template,
        ]);
        return $user->fresh(['card']);
    }

    public function test_salva_responsavel_legal_opcional(): void
    {
        $user = $this->makeUserWithCard();

        Livewire::actingAs($user)
            ->test(ProfileEditor::class, ['card' => $user->card])
            ->set('campaign_name', 'Chapa X')
            ->set('legal_responsible_name', 'Maria da Silva')
            ->set('legal_responsible_document', '123.456.789-00')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('campaign_profiles', [
            'card_id' => $user->card->id,
            'legal_responsible_name' => 'Maria da Silva',
            'legal_responsible_document' => '123.456.789-00',
        ]);
    }

    public function test_responsavel_legal_e_opcional(): void
    {
        $user = $this->makeUserWithCard();

        Livewire::actingAs($user)
            ->test(ProfileEditor::class, ['card' => $user->card])
            ->set('campaign_name', 'Chapa Y')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('campaign_profiles', [
            'card_id' => $user->card->id,
            'legal_responsible_name' => null,
        ]);
    }

    public function test_aviso_de_conformidade_tse_aparece_no_editor_do_dashboard(): void
    {
        $user = $this->makeUserWithCard();

        $response = $this->actingAs($user)->get(route('dashboard.campaign.profile'));

        $response->assertOk();
        $response->assertSee('TSE');
    }

    public function test_rodape_legal_aparece_no_cartao_publico_de_campanha(): void
    {
        $user = $this->makeUserWithCard();
        $user->card->campaignProfile()->create([
            'campaign_name' => 'Chapa Z',
            'legal_responsible_name' => 'João Pereira',
            'legal_responsible_document' => '987.654.321-00',
        ]);

        $response = $this->get('/u/' . $user->card->slug);

        $response->assertOk();
        $response->assertSee('João Pereira');
        $response->assertSee('987.654.321-00');
        $response->assertSee('TSE');
    }

    public function test_rodape_legal_sem_responsavel_ainda_mostra_aviso_generico(): void
    {
        $user = $this->makeUserWithCard();
        $user->card->campaignProfile()->create(['campaign_name' => 'Chapa W']);

        $response = $this->get('/u/' . $user->card->slug);

        $response->assertOk();
        $response->assertSee('responsabilidade exclusiva do titular');
    }

    public function test_pagina_de_termos_carrega_e_contem_secao_de_campanhas_eleitorais(): void
    {
        $response = $this->get(route('legal.termos'));

        $response->assertOk();
        $response->assertSee('Uso em campanhas eleitorais e eleições internas');
        $response->assertSee('Tribunal Superior Eleitoral');
    }

    public function test_pagina_de_termos_nao_tem_secoes_duplicadas(): void
    {
        $response = $this->get(route('legal.termos'));

        $response->assertOk();
        // Antes desta correção, o documento continha duas cópias completas do
        // texto — o teste garante que a seção 1 aparece uma única vez.
        $content = $response->getContent();
        $this->assertSame(1, substr_count($content, 'id="t1"'));
    }
}
