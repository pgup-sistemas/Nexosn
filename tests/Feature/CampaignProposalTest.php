<?php

namespace Tests\Feature;

use App\Livewire\Campaign\ProposalManager;
use App\Models\CampaignProposalCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CampaignProposalTest extends TestCase
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

    public function test_cria_proposta(): void
    {
        $user = $this->makeUserWithCard();

        Livewire::actingAs($user)
            ->test(ProposalManager::class, ['card' => $user->card])
            ->set('title', 'Educação de qualidade')
            ->set('description', 'Ampliar investimento em creches.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('campaign_proposals', [
            'card_id' => $user->card->id,
            'title' => 'Educação de qualidade',
        ]);
    }

    public function test_rejeita_video_de_dominio_nao_permitido(): void
    {
        $user = $this->makeUserWithCard();

        Livewire::actingAs($user)
            ->test(ProposalManager::class, ['card' => $user->card])
            ->set('title', 'Proposta X')
            ->set('video_url', 'https://malicioso.com/video')
            ->call('save')
            ->assertHasErrors('video_url');

        $this->assertDatabaseMissing('campaign_proposals', ['title' => 'Proposta X']);
    }

    public function test_aceita_video_do_youtube(): void
    {
        $user = $this->makeUserWithCard();

        Livewire::actingAs($user)
            ->test(ProposalManager::class, ['card' => $user->card])
            ->set('title', 'Proposta com vídeo')
            ->set('video_url', 'https://www.youtube.com/watch?v=abc123')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('campaign_proposals', [
            'title' => 'Proposta com vídeo',
            'video_url' => 'https://www.youtube.com/watch?v=abc123',
        ]);
    }

    public function test_categoria_pode_ser_associada(): void
    {
        $user = $this->makeUserWithCard();
        $category = CampaignProposalCategory::create(['card_id' => $user->card->id, 'name' => 'Saúde', 'order' => 0]);

        Livewire::actingAs($user)
            ->test(ProposalManager::class, ['card' => $user->card])
            ->set('title', 'Mais postos de saúde')
            ->set('category_id', $category->id)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('campaign_proposals', [
            'title' => 'Mais postos de saúde',
            'category_id' => $category->id,
        ]);
    }

    public function test_rejeita_arquivo_pdf_falso(): void
    {
        Storage::fake('local');
        $user = $this->makeUserWithCard();

        $fakePdf = UploadedFile::fake()->createWithContent('fake.pdf', 'not a real pdf');

        Livewire::actingAs($user)
            ->test(ProposalManager::class, ['card' => $user->card])
            ->set('title', 'Proposta com PDF falso')
            ->set('pdf_upload', $fakePdf)
            ->call('save');

        $this->assertDatabaseMissing('campaign_proposals', ['title' => 'Proposta com PDF falso', 'pdf_path' => null]);
    }

    public function test_remove_proposta(): void
    {
        $user = $this->makeUserWithCard();
        $proposal = $user->card->campaignProposals()->create(['title' => 'A remover', 'order' => 0]);

        Livewire::actingAs($user)
            ->test(ProposalManager::class, ['card' => $user->card])
            ->call('delete', $proposal->id);

        $this->assertDatabaseMissing('campaign_proposals', ['id' => $proposal->id]);
    }
}
