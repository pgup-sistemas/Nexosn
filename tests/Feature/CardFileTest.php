<?php

namespace Tests\Feature;

use App\Livewire\Campaign\FileManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class CardFileTest extends TestCase
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

    private function realPdf(string $name = 'plano.pdf'): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, "%PDF-1.4\n%%EOF");
    }

    public function test_upload_de_arquivo_pdf_valido(): void
    {
        $user = $this->makeUserWithCard();

        Livewire::actingAs($user)
            ->test(FileManager::class, ['card' => $user->card])
            ->set('label', 'Plano de Gestão 2026')
            ->set('category', 'management_plan')
            ->set('file_upload', $this->realPdf())
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('card_files', [
            'card_id' => $user->card->id,
            'label' => 'Plano de Gestão 2026',
            'category' => 'management_plan',
        ]);
    }

    public function test_download_retorna_404_para_cartao_inativo(): void
    {
        $user = $this->makeUserWithCard();
        $file = $user->card->files()->create([
            'label' => 'Arquivo', 'category' => 'material',
            'file_path' => 'cards/1/documents/x.pdf', 'file_type' => 'pdf', 'order' => 0,
        ]);
        $user->card->update(['is_active' => false]);

        $response = $this->get(route('card.file.download', ['card' => $user->card->slug, 'file' => $file->id]));

        $response->assertNotFound();
    }

    public function test_download_retorna_404_quando_arquivo_pertence_a_outro_cartao(): void
    {
        $userA = $this->makeUserWithCard();
        $userB = $this->makeUserWithCard();
        $fileB = $userB->card->files()->create([
            'label' => 'Arquivo B', 'category' => 'material',
            'file_path' => 'cards/2/documents/y.pdf', 'file_type' => 'pdf', 'order' => 0,
        ]);

        $response = $this->get(route('card.file.download', ['card' => $userA->card->slug, 'file' => $fileB->id]));

        $response->assertNotFound();
    }

    public function test_remove_arquivo(): void
    {
        $user = $this->makeUserWithCard();
        $file = $user->card->files()->create([
            'label' => 'A remover', 'category' => 'other',
            'file_path' => 'cards/1/documents/z.pdf', 'file_type' => 'pdf', 'order' => 0,
        ]);

        Livewire::actingAs($user)
            ->test(FileManager::class, ['card' => $user->card])
            ->call('delete', $file->id);

        $this->assertDatabaseMissing('card_files', ['id' => $file->id]);
    }
}
