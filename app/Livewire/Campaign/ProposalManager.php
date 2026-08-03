<?php

namespace App\Livewire\Campaign;

use App\Models\Card;
use App\Services\CampaignMediaService;
use App\Services\ImageService;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProposalManager extends Component
{
    use WithFileUploads;

    public Card $card;

    public bool $showForm = false;
    public ?int $editingId = null;

    public string $title = '';
    public string $description = '';
    public string $video_url = '';
    public ?int $category_id = null;
    public bool $is_active = true;

    public $image_upload = null;
    public $pdf_upload = null;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:120',
            'description' => 'nullable|string|max:2000',
            'video_url' => 'nullable|url|max:255',
            'category_id' => 'nullable|integer',
            'is_active' => 'boolean',
            'image_upload' => 'nullable|image|max:4096',
            'pdf_upload' => 'nullable|file|mimes:pdf|max:10240',
        ];
    }

    public function mount(Card $card): void
    {
        $this->card = $card;
    }

    public function startCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function startEdit(int $id): void
    {
        $proposal = $this->card->campaignProposals()->findOrFail($id);
        $this->editingId = $id;
        $this->title = $proposal->title;
        $this->description = $proposal->description ?? '';
        $this->video_url = $proposal->video_url ?? '';
        $this->category_id = $proposal->category_id;
        $this->is_active = $proposal->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $mediaService = app(CampaignMediaService::class);
        $videoUrl = $mediaService->assertAllowedVideoUrl($this->video_url ?: null);

        $data = [
            'title' => trim($this->title),
            'description' => trim($this->description) ?: null,
            'video_url' => $videoUrl,
            'category_id' => $this->category_id,
            'is_active' => $this->is_active,
        ];

        if ($this->image_upload) {
            $data['image'] = app(ImageService::class)->storePhoto($this->image_upload, $this->card->user_id, 'campaign/proposals');
        }

        if ($this->pdf_upload) {
            $data['pdf_path'] = $mediaService->storePdf($this->pdf_upload, $this->card->id);
        }

        if ($this->editingId) {
            $this->card->campaignProposals()->findOrFail($this->editingId)->update($data);
            session()->flash('sucesso', 'Proposta atualizada.');
        } else {
            $data['order'] = $this->card->campaignProposals()->count();
            $this->card->campaignProposals()->create($data);
            session()->flash('sucesso', 'Proposta criada.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        $this->card->campaignProposals()->findOrFail($id)->delete();
        session()->flash('sucesso', 'Proposta removida.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->title = '';
        $this->description = '';
        $this->video_url = '';
        $this->category_id = null;
        $this->is_active = true;
        $this->image_upload = null;
        $this->pdf_upload = null;
        $this->editingId = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.campaign.proposal-manager', [
            'proposals' => $this->card->campaignProposals()->with('category')->get(),
            'categories' => $this->card->campaignProposalCategories()->get(),
        ]);
    }
}
