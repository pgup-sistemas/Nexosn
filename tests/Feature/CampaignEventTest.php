<?php

namespace Tests\Feature;

use App\Livewire\Campaign\EventManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CampaignEventTest extends TestCase
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

    public function test_cria_evento_de_campanha_com_local_e_mapa(): void
    {
        $user = $this->makeUserWithCard();

        Livewire::actingAs($user)
            ->test(EventManager::class, ['card' => $user->card])
            ->set('title', 'Comitê Central')
            ->set('event_date', '2026-09-10')
            ->set('event_time', '19:00')
            ->set('location', 'Av. Principal, 100')
            ->set('map_url', 'https://maps.google.com/?q=-8.76,-63.90')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('campaign_events', [
            'card_id' => $user->card->id,
            'title' => 'Comitê Central',
            'location' => 'Av. Principal, 100',
        ]);
    }

    public function test_exige_data_do_evento(): void
    {
        $user = $this->makeUserWithCard();

        Livewire::actingAs($user)
            ->test(EventManager::class, ['card' => $user->card])
            ->set('title', 'Sem data')
            ->call('save')
            ->assertHasErrors('event_date');
    }

    public function test_remove_evento(): void
    {
        $user = $this->makeUserWithCard();
        $event = $user->card->campaignEvents()->create([
            'title' => 'A remover', 'event_date' => '2026-09-10', 'order' => 0,
        ]);

        Livewire::actingAs($user)
            ->test(EventManager::class, ['card' => $user->card])
            ->call('delete', $event->id);

        $this->assertDatabaseMissing('campaign_events', ['id' => $event->id]);
    }
}
