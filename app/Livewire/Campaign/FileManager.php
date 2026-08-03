<?php

namespace App\Livewire\Campaign;

use App\Models\Card;
use App\Services\CampaignMediaService;
use Livewire\Component;
use Livewire\WithFileUploads;

class FileManager extends Component
{
    use WithFileUploads;

    public Card $card;

    public string $label = '';
    public string $category = 'material';
    public $file_upload = null;

    protected function rules(): array
    {
        return [
            'label' => 'required|string|max:120',
            'category' => 'required|in:management_plan,material,other',
            'file_upload' => 'required|file|mimes:pdf|max:10240',
        ];
    }

    public function mount(Card $card): void
    {
        $this->card = $card;
    }

    public function save(): void
    {
        $this->validate();

        $path = app(CampaignMediaService::class)->storePdf($this->file_upload, $this->card->id);

        $this->card->files()->create([
            'label' => trim($this->label),
            'category' => $this->category,
            'file_path' => $path,
            'file_type' => 'pdf',
            'order' => $this->card->files()->count(),
        ]);

        $this->reset(['label', 'file_upload']);
        $this->category = 'material';
        session()->flash('sucesso', 'Arquivo enviado.');
    }

    public function delete(int $id): void
    {
        $file = $this->card->files()->findOrFail($id);
        app(CampaignMediaService::class)->delete($file->file_path);
        $file->delete();
        session()->flash('sucesso', 'Arquivo removido.');
    }

    public function render()
    {
        return view('livewire.campaign.file-manager', [
            'files' => $this->card->files()->get(),
        ]);
    }
}
