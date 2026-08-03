<?php

namespace Tests\Feature;

use App\Jobs\SendContactMessage;
use App\Livewire\Card\ContactForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormPurposeTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_salva_finalidade_quando_opcoes_fornecidas(): void
    {
        Queue::fake();
        $user = $this->makeUserWithCard();

        Livewire::test(ContactForm::class, [
            'card' => $user->card,
            'purposeOptions' => ['voluntario' => 'Quero ser voluntário', 'sugestao' => 'Sugestão'],
        ])
            ->set('purpose', 'voluntario')
            ->set('senderName', 'João')
            ->set('senderEmail', 'joao@example.com')
            ->set('message', 'Quero ajudar na campanha.')
            ->call('submit');

        $this->assertDatabaseHas('contact_messages', [
            'card_id' => $user->card->id,
            'purpose' => 'voluntario',
        ]);
    }

    public function test_finalidade_fica_nula_sem_opcoes_configuradas(): void
    {
        Queue::fake();
        $user = $this->makeUserWithCard();

        Livewire::test(ContactForm::class, ['card' => $user->card])
            ->set('senderName', 'Maria')
            ->set('senderEmail', 'maria@example.com')
            ->set('message', 'Mensagem de contato padrão.')
            ->call('submit');

        $this->assertDatabaseHas('contact_messages', [
            'card_id' => $user->card->id,
            'purpose' => null,
        ]);
    }
}
