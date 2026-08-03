<?php

namespace Tests\Feature;

use App\Livewire\Campaign\TeamManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CampaignTeamTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithCard(): User
    {
        $user = User::factory()->create([
            'plan' => 'pro',
            'plan_expires_at' => now()->addMonth(),
        ]);
        $user->card()->create([
            'slug'         => 'titular-' . $user->id,
            'display_name' => 'Titular Teste',
            'is_active'    => true,
        ]);
        return $user->fresh(['card']);
    }

    public function test_cria_membro_da_equipe(): void
    {
        $user = $this->makeUserWithCard();

        Livewire::actingAs($user)
            ->test(TeamManager::class, ['card' => $user->card])
            ->set('name', 'Maria Silva')
            ->set('role', 'Vice')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('campaign_team_members', [
            'card_id' => $user->card->id,
            'name' => 'Maria Silva',
            'role' => 'Vice',
        ]);
    }

    public function test_edita_membro_existente(): void
    {
        $user = $this->makeUserWithCard();
        $member = $user->card->campaignTeamMembers()->create(['name' => 'Nome Antigo', 'order' => 0]);

        Livewire::actingAs($user)
            ->test(TeamManager::class, ['card' => $user->card])
            ->call('startEdit', $member->id)
            ->set('name', 'Nome Novo')
            ->call('save');

        $this->assertDatabaseCount('campaign_team_members', 1);
        $this->assertDatabaseHas('campaign_team_members', ['id' => $member->id, 'name' => 'Nome Novo']);
    }

    public function test_remove_membro_da_equipe(): void
    {
        $user = $this->makeUserWithCard();
        $member = $user->card->campaignTeamMembers()->create(['name' => 'A remover', 'order' => 0]);

        Livewire::actingAs($user)
            ->test(TeamManager::class, ['card' => $user->card])
            ->call('delete', $member->id);

        $this->assertDatabaseMissing('campaign_team_members', ['id' => $member->id]);
    }
}
