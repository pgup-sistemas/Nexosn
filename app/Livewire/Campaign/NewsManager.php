<?php

namespace App\Livewire\Campaign;

use App\Models\Card;
use App\Services\ImageService;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewsManager extends Component
{
    use WithFileUploads;

    public Card $card;

    public bool $showForm = false;
    public ?int $editingId = null;

    public string $title = '';
    public string $body = '';
    public string $published_at = '';
    public bool $is_active = true;

    public $cover_image_upload = null;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'body' => 'nullable|string|max:5000',
            'published_at' => 'nullable|date',
            'is_active' => 'boolean',
            'cover_image_upload' => 'nullable|image|max:4096',
        ];
    }

    public function mount(Card $card): void
    {
        $this->card = $card;
        $this->published_at = now()->format('Y-m-d\TH:i');
    }

    public function startCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function startEdit(int $id): void
    {
        $news = $this->card->campaignNews()->findOrFail($id);
        $this->editingId = $id;
        $this->title = $news->title;
        $this->body = $news->body ?? '';
        $this->published_at = optional($news->published_at)->format('Y-m-d\TH:i') ?? '';
        $this->is_active = $news->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title' => trim($this->title),
            'body' => trim($this->body) ?: null,
            'published_at' => $this->published_at ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->cover_image_upload) {
            $data['cover_image'] = app(ImageService::class)->storePhoto($this->cover_image_upload, $this->card->user_id, 'campaign/news');
        }

        if ($this->editingId) {
            $this->card->campaignNews()->findOrFail($this->editingId)->update($data);
            session()->flash('sucesso', 'Notícia atualizada.');
        } else {
            $data['order'] = $this->card->campaignNews()->count();
            $this->card->campaignNews()->create($data);
            session()->flash('sucesso', 'Notícia publicada.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        $this->card->campaignNews()->findOrFail($id)->delete();
        session()->flash('sucesso', 'Notícia removida.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->title = '';
        $this->body = '';
        $this->published_at = now()->format('Y-m-d\TH:i');
        $this->is_active = true;
        $this->cover_image_upload = null;
        $this->editingId = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.campaign.news-manager', [
            'newsItems' => $this->card->campaignNews()->get(),
        ]);
    }
}
