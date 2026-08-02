<?php

namespace App\Livewire\Card;

use App\Models\Card;
use App\Services\ImageService;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CardEditor extends Component
{
    use WithFileUploads;

    public ?Card $card = null;

    #[Validate('nullable|image|max:2048')]
    public $profile_photo_upload = null;

    #[Validate('nullable|image|max:4096')]
    public $cover_photo_upload = null;

    #[Validate('required|string|min:3|max:50|alpha_dash|lowercase')]
    public string $slug = '';

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

    #[Validate('nullable|string|max:20')]
    public string $contact_landline = '';

    #[Validate('nullable|string|max:255')]
    public string $address = '';

    #[Validate('nullable|string|max:100')]
    public string $city = '';

    #[Validate('nullable|string|max:2')]
    public string $state = '';

    #[Validate('nullable|string|max:9')]
    public string $zip_code = '';

    #[Validate('nullable|string|max:100')]
    public string $country = 'Brasil';

    public ?string $cepErro = null;

    #[Validate('nullable|url|max:255')]
    public string $website = '';

    #[Validate('nullable|string|max:100')]
    public string $pix_key = '';

    #[Validate('nullable|in:default,dark')]
    public string $template = 'default';

    #[Validate('nullable|regex:/^#[0-9A-Fa-f]{6}$/')]
    public string $brand_color_primary = '';

    #[Validate('nullable|regex:/^#[0-9A-Fa-f]{6}$/')]
    public string $brand_color_button = '';

    public function mount(): void
    {
        $this->card = auth()->user()->card;

        if ($this->card) {
            $this->slug                = $this->card->slug ?? '';
            $this->display_name        = $this->card->display_name ?? '';
            $this->title               = $this->card->title ?? '';
            $this->company             = $this->card->company ?? '';
            $this->bio                 = $this->card->bio ?? '';
            $this->contact_email       = $this->card->contact_email ?? '';
            $this->contact_phone       = $this->card->contact_phone ?? '';
            $this->contact_landline    = $this->card->contact_landline ?? '';
            $this->address             = $this->card->address ?? '';
            $this->city                = $this->card->city ?? '';
            $this->state               = $this->card->state ?? '';
            $this->zip_code            = $this->card->zip_code ?? '';
            $this->country             = $this->card->country ?? 'Brasil';
            $this->website             = $this->card->website ?? '';
            $this->pix_key             = $this->card->pix_key ?? '';
            $this->template            = $this->card->template ?? 'default';
            $this->brand_color_primary = $this->card->brand_color_primary ?? '#003049';
            $this->brand_color_button  = $this->card->brand_color_button  ?? '#D62828';
        }
    }

    /**
     * Busca endereço pelo CEP via API ViaCEP (gratuita, sem chave) e preenche
     * automaticamente rua/bairro, cidade e estado.
     */
    public function buscarCep(): void
    {
        $this->cepErro = null;
        $cep = preg_replace('/\D/', '', $this->zip_code);

        if (strlen($cep) !== 8) {
            $this->cepErro = 'CEP inválido. Digite os 8 números do CEP.';
            return;
        }

        try {
            $response = Http::timeout(5)->get("https://viacep.com.br/ws/{$cep}/json/");
        } catch (\Throwable $e) {
            $this->cepErro = 'Não foi possível consultar o CEP agora. Tente novamente.';
            return;
        }

        if (! $response->ok() || $response->json('erro')) {
            $this->cepErro = 'CEP não encontrado.';
            return;
        }

        $data = $response->json();

        $logradouro = trim(($data['logradouro'] ?? '') . ($data['bairro'] ? ', ' . $data['bairro'] : ''));

        if ($logradouro && ! $this->address) {
            $this->address = $logradouro;
        }

        $this->city  = $data['localidade'] ?? $this->city;
        $this->state = $data['uf'] ?? $this->state;
    }

    public function removeProfilePhoto(): void
    {
        $card = $this->card;
        if ($card->profile_photo) {
            app(ImageService::class)->delete($card->profile_photo);
            $card->update(['profile_photo' => null]);
        }
    }

    public function removeCoverPhoto(): void
    {
        $card = $this->card;
        if ($card->cover_photo) {
            app(ImageService::class)->delete($card->cover_photo);
            $card->update(['cover_photo' => null]);
        }
    }

    public function save(): void
    {
        $this->validateOnly('slug', [
            'slug' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'lowercase',
                       \Illuminate\Validation\Rule::unique('cards', 'slug')->ignore($this->card->id)],
        ]);

        $validated = $this->validate(array_filter([
            'display_name'       => 'required|string|max:80',
            'title'              => 'nullable|string|max:80',
            'company'            => 'nullable|string|max:80',
            'bio'                => 'nullable|string|max:500',
            'contact_email'      => 'nullable|email|max:255',
            'contact_phone'      => 'nullable|string|max:20',
            'contact_landline'   => 'nullable|string|max:20',
            'address'            => 'nullable|string|max:255',
            'city'               => 'nullable|string|max:100',
            'state'              => 'nullable|string|max:2',
            'zip_code'           => 'nullable|string|max:9',
            'country'            => 'nullable|string|max:100',
            'website'            => 'nullable|url|max:255',
            'pix_key'            => 'nullable|string|max:100',
            'template'           => 'nullable|in:default,dark',
            'brand_color_primary'=> 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'brand_color_button' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
        ]));
        $user = auth()->user();
        $imageService = app(ImageService::class);

        if ($this->profile_photo_upload) {
            if ($this->card->profile_photo) {
                $imageService->delete($this->card->profile_photo);
            }
            $path = $imageService->storeProfile($this->profile_photo_upload, $user->id);
            $this->card->update(['profile_photo' => $path]);
            $this->reset('profile_photo_upload');
        }

        if ($this->cover_photo_upload) {
            if ($this->card->cover_photo) {
                $imageService->delete($this->card->cover_photo);
            }
            $path = $imageService->storeCover($this->cover_photo_upload, $user->id);
            $this->card->update(['cover_photo' => $path]);
            $this->reset('cover_photo_upload');
        }

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

        // Slug: salva separado para garantir unicidade com Rule::unique
        if ($this->slug !== $this->card->slug) {
            $this->card->update(['slug' => $this->slug]);
        }

        session()->flash('sucesso', 'Perfil atualizado com sucesso!');
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
