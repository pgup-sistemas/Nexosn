<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignCollapsibleSectionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cartao_de_campanha_renderiza_cards_recolhiveis(): void
    {
        $user = User::factory()->create([
            'plan' => 'pro',
            'plan_expires_at' => now()->addMonth(),
        ]);
        $user->card()->create([
            'slug' => 'titular-' . $user->id,
            'display_name' => 'Titular Teste',
            'is_active' => true,
            'template' => 'campaign-hero',
        ]);
        $card = $user->fresh(['card'])->card;
        $card->campaignProfile()->create(['campaign_name' => 'Chapa X']);
        $card->campaignProposals()->create(['title' => 'Proposta X', 'order' => 0]);

        $response = $this->get('/u/' . $card->slug);

        $response->assertOk();
        $response->assertSee('cs-toggle', false);
        $response->assertSee('cs-collapsible', false);
        $response->assertSee('Enviar mensagem');
        // Cabeçalho interno do formulário de contato não deve duplicar o título do card
        $response->assertSee('contact-form-heading', false);
    }
}
