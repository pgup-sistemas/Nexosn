<?php

namespace App\Livewire\Campaign;

use App\Models\Card;
use App\Services\ImageService;
use Livewire\Component;
use Livewire\WithFileUploads;

class TeamManager extends Component
{
    use WithFileUploads;

    public Card $card;

    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $role = '';
    public bool $is_active = true;

    public $photo_upload = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:80',
            'role' => 'nullable|string|max:80',
            'is_active' => 'boolean',
            'photo_upload' => 'nullable|image|max:4096',
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
        $member = $this->card->campaignTeamMembers()->findOrFail($id);
        $this->editingId = $id;
        $this->name = $member->name;
        $this->role = $member->role ?? '';
        $this->is_active = $member->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => trim($this->name),
            'role' => trim($this->role) ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->photo_upload) {
            $data['photo'] = app(ImageService::class)->storeProfile($this->photo_upload, $this->card->user_id);
        }

        if ($this->editingId) {
            $this->card->campaignTeamMembers()->findOrFail($this->editingId)->update($data);
            session()->flash('sucesso', 'Membro da equipe atualizado.');
        } else {
            $data['order'] = $this->card->campaignTeamMembers()->count();
            $this->card->campaignTeamMembers()->create($data);
            session()->flash('sucesso', 'Membro adicionado à equipe.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        $this->card->campaignTeamMembers()->findOrFail($id)->delete();
        session()->flash('sucesso', 'Membro removido.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->role = '';
        $this->is_active = true;
        $this->photo_upload = null;
        $this->editingId = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.campaign.team-manager', [
            'members' => $this->card->campaignTeamMembers()->get(),
        ]);
    }
}
