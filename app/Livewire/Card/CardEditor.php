<?php

namespace App\Livewire\Card;

use App\Models\Card;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CardEditor extends Component
{
    public ?Card $card = null;

    #[Validate('required|string|max:80')]
    public string $display_name = '';

    #[Validate('nullable|string|max:80')]
    public string $title = '';

    #[Validate('nullable|string|max:80')]
    public string $company = '';

    #[Validate('nullable|string|max:500')]
    public string $bio = '';

    #[Validate('nullable|email|max:255')]
    public string $contact_email = '';

    #[Validate('nullable|string|max:20')]
    public string $contact_phone = '';

    #[Validate('nullable|string|max:255')]
    public string $address = '';

    #[Validate('nullable|url|max:255')]
    public string $website = '';

    #[Validate('nullable|string|max:100')]
    public string $pix_key = '';

    #[Validate('nullable|regex:/^#[0-9A-Fa-f]{6}$/')]
    public string $brand_color_primary = '';

    #[Validate('nullable|regex:/^#[0-9A-Fa-f]{6}$/')]
    public string $brand_color_button = '';

    public function mount(): void
    {
        $this->card = auth()->user()->card;

        if ($this->card) {
            $this->display_name        = $this->card->display_name ?? '';
            $this->title               = $this->card->title ?? '';
            $this->company             = $this->card->company ?? '';
            $this->bio                 = $this->card->bio ?? '';
            $this->contact_email       = $this->card->contact_email ?? '';
            $this->contact_phone       = $this->card->contact_phone ?? '';
            $this->address             = $this->card->address ?? '';
            $this->website             = $this->card->website ?? '';
            $this->pix_key             = $this->card->pix_key ?? '';
            $this->brand_color_primary = $this->card->brand_color_primary ?? '#003049';
            $this->brand_color_button  = $this->card->brand_color_button  ?? '#D62828';
        }
    }

    public function save(): void
    {
        $validated = $this->validate();
        $user = auth()->user();

        // Valida HEX no backend (rule já garante, mas registramos o cuidado)
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $this->brand_color_primary)) {
            $this->brand_color_primary = '#003049';
        }
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $this->brand_color_button)) {
            $this->brand_color_button = '#D62828';
        }

        $data = array_filter($validated, fn ($v) => $v !== null && $v !== '');

        // Cores só salvas se for Pro/Trial
        if (!$user->isPro() && !$user->isOnTrial()) {
            unset($data['brand_color_primary'], $data['brand_color_button']);
        }

        $this->card->update($data);

        session()->flash('sucesso', 'Cartão atualizado com sucesso!');
        $this->dispatch('card-updated');
    }

    public function toggleActive(): void
    {
        $this->card->update(['is_active' => !$this->card->is_active]);
        $this->card->refresh();
    }

    public function render()
    {
        $isPro = auth()->user()->isPro() || auth()->user()->isOnTrial();
        return view('livewire.card.card-editor', compact('isPro'));
    }
}
