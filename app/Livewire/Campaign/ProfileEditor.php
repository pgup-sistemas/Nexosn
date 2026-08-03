<?php

namespace App\Livewire\Campaign;

use App\Models\Card;
use App\Models\CampaignProfile;
use App\Services\ImageService;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileEditor extends Component
{
    use WithFileUploads;

    public Card $card;

    #[Validate('nullable|image|max:4096')]
    public $portrait_photo_upload = null;

    #[Validate('nullable|string|max:80')]
    public string $campaign_name = '';

    #[Validate('nullable|string|max:60')]
    public string $role_title = '';

    #[Validate('nullable|string|max:20')]
    public string $ballot_number = '';

    #[Validate('nullable|string|max:80')]
    public string $organization_name = '';

    #[Validate('nullable|string|max:60')]
    public string $affiliation = '';

    #[Validate('nullable|string|max:120')]
    public string $slogan = '';

    #[Validate('nullable|date')]
    public string $countdown_target_at = '';

    #[Validate('nullable|string|max:255')]
    public string $hq_address = '';

    #[Validate('nullable|string|max:150')]
    public string $legal_responsible_name = '';

    #[Validate('nullable|string|max:30')]
    public string $legal_responsible_document = '';

    public function mount(Card $card): void
    {
        $this->card = $card;
        $profile = $card->campaignProfile;

        if ($profile) {
            $this->campaign_name = (string) $profile->campaign_name;
            $this->role_title = (string) $profile->role_title;
            $this->ballot_number = (string) $profile->ballot_number;
            $this->organization_name = (string) $profile->organization_name;
            $this->affiliation = (string) $profile->affiliation;
            $this->slogan = (string) $profile->slogan;
            $this->countdown_target_at = optional($profile->countdown_target_at)->format('Y-m-d\TH:i') ?? '';
            $this->hq_address = (string) $profile->hq_address;
            $this->legal_responsible_name = (string) $profile->legal_responsible_name;
            $this->legal_responsible_document = (string) $profile->legal_responsible_document;
        }
    }

    public function save(): void
    {
        $this->validate();

        $profile = $this->card->campaignProfile ?? new CampaignProfile(['card_id' => $this->card->id]);

        if ($this->portrait_photo_upload) {
            $imageService = app(ImageService::class);
            if ($profile->portrait_photo) {
                $imageService->delete($profile->portrait_photo);
            }
            $profile->portrait_photo = $imageService->storeProfile($this->portrait_photo_upload, $this->card->user_id);
        }

        $profile->fill([
            'campaign_name' => $this->campaign_name ?: null,
            'role_title' => $this->role_title ?: null,
            'ballot_number' => $this->ballot_number ?: null,
            'organization_name' => $this->organization_name ?: null,
            'affiliation' => $this->affiliation ?: null,
            'slogan' => $this->slogan ?: null,
            'countdown_target_at' => $this->countdown_target_at ?: null,
            'hq_address' => $this->hq_address ?: null,
            'legal_responsible_name' => $this->legal_responsible_name ?: null,
            'legal_responsible_document' => $this->legal_responsible_document ?: null,
        ]);
        $profile->card_id = $this->card->id;
        $profile->save();

        $this->portrait_photo_upload = null;
        session()->flash('sucesso', 'Perfil de campanha salvo.');
    }

    public function render()
    {
        return view('livewire.campaign.profile-editor');
    }
}
