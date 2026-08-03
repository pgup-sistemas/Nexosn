<?php

namespace Tests\Feature;

use App\Livewire\Campaign\NewsManager;
use App\Livewire\Campaign\TimelineManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CampaignNewsAndTimelineTest extends TestCase
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

    public function test_cria_noticia(): void
    {
        $user = $this->makeUserWithCard();

        Livewire::actingAs($user)
            ->test(NewsManager::class, ['card' => $user->card])
            ->set('title', 'Lançamento da campanha')
            ->set('body', 'Hoje iniciamos oficialmente.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('campaign_news', [
            'card_id' => $user->card->id,
            'title' => 'Lançamento da campanha',
        ]);
    }

    public function test_remove_noticia(): void
    {
        $user = $this->makeUserWithCard();
        $news = $user->card->campaignNews()->create(['title' => 'A remover', 'order' => 0]);

        Livewire::actingAs($user)
            ->test(NewsManager::class, ['card' => $user->card])
            ->call('delete', $news->id);

        $this->assertDatabaseMissing('campaign_news', ['id' => $news->id]);
    }

    public function test_cria_evento_na_linha_do_tempo(): void
    {
        $user = $this->makeUserWithCard();

        Livewire::actingAs($user)
            ->test(TimelineManager::class, ['card' => $user->card])
            ->set('occurred_on', '2020-01-15')
            ->set('title', 'Fundação do movimento')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('campaign_timeline_items', [
            'card_id' => $user->card->id,
            'title' => 'Fundação do movimento',
        ]);
    }

    public function test_exige_data_no_evento_da_linha_do_tempo(): void
    {
        $user = $this->makeUserWithCard();

        Livewire::actingAs($user)
            ->test(TimelineManager::class, ['card' => $user->card])
            ->set('title', 'Sem data')
            ->call('save')
            ->assertHasErrors('occurred_on');
    }

    public function test_remove_evento_da_linha_do_tempo(): void
    {
        $user = $this->makeUserWithCard();
        $item = $user->card->campaignTimelineItems()->create([
            'occurred_on' => '2020-01-15', 'title' => 'A remover', 'order' => 0,
        ]);

        Livewire::actingAs($user)
            ->test(TimelineManager::class, ['card' => $user->card])
            ->call('delete', $item->id);

        $this->assertDatabaseMissing('campaign_timeline_items', ['id' => $item->id]);
    }
}
