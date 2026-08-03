<?php

namespace Tests\Feature;

use App\Livewire\Campaign\ProfileEditor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CampaignProfileTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithCard(bool $pro = true): User
    {
        $user = User::factory()->create([
            'plan' => $pro ? 'pro' : 'free',
            'plan_expires_at' => $pro ? now()->addMonth() : null,
        ]);
        $user->card()->create([
            'slug'         => 'titular-' . $user->id,
            'display_name' => 'Titular Teste',
            'is_active'    => true,
        ]);
        return $user->fresh(['card']);
    }

    public function test_free_e_bloqueado_da_pagina_de_perfil_de_campanha(): void
    {
        $user = $this->makeUserWithCard(pro: false);

        $response = $this->actingAs($user)->get(route('dashboard.campaign.profile'));

        $response->assertRedirect(route('dashboard.plan'));
    }

    public function test_pro_acessa_pagina_de_perfil_de_campanha(): void
    {
        $user = $this->makeUserWithCard(pro: true);

        $response = $this->actingAs($user)->get(route('dashboard.campaign.profile'));

        $response->assertOk();
    }

    public function test_salva_perfil_de_campanha(): void
    {
        $user = $this->makeUserWithCard(pro: true);

        Livewire::actingAs($user)
            ->test(ProfileEditor::class, ['card' => $user->card])
            ->set('campaign_name', 'Chapa Renovação')
            ->set('role_title', 'Presidente')
            ->set('ballot_number', '13')
            ->set('organization_name', 'Sindicato dos Metalúrgicos')
            ->set('slogan', 'Juntos somos mais fortes')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('campaign_profiles', [
            'card_id' => $user->card->id,
            'campaign_name' => 'Chapa Renovação',
            'role_title' => 'Presidente',
            'ballot_number' => '13',
        ]);
    }

    public function test_atualiza_perfil_existente_sem_duplicar(): void
    {
        $user = $this->makeUserWithCard(pro: true);

        Livewire::actingAs($user)
            ->test(ProfileEditor::class, ['card' => $user->card])
            ->set('campaign_name', 'Nome Inicial')
            ->call('save');

        Livewire::actingAs($user)
            ->test(ProfileEditor::class, ['card' => $user->card])
            ->set('campaign_name', 'Nome Atualizado')
            ->call('save');

        $this->assertDatabaseCount('campaign_profiles', 1);
        $this->assertDatabaseHas('campaign_profiles', [
            'card_id' => $user->card->id,
            'campaign_name' => 'Nome Atualizado',
        ]);
    }
}
