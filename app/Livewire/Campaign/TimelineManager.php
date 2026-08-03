<?php

namespace App\Livewire\Campaign;

use App\Models\Card;
use Livewire\Component;

class TimelineManager extends Component
{
    public Card $card;

    public bool $showForm = false;
    public ?int $editingId = null;

    public string $occurred_on = '';
    public string $title = '';
    public string $description = '';
    public string $icon = 'flag';

    protected function rules(): array
    {
        return [
            'occurred_on' => 'required|date',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:40',
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
        $item = $this->card->campaignTimelineItems()->findOrFail($id);
        $this->editingId = $id;
        $this->occurred_on = $item->occurred_on->format('Y-m-d');
        $this->title = $item->title;
        $this->description = $item->description ?? '';
        $this->icon = $item->icon ?? 'flag';
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'occurred_on' => $this->occurred_on,
            'title' => trim($this->title),
            'description' => trim($this->description) ?: null,
            'icon' => $this->icon ?: 'flag',
        ];

        if ($this->editingId) {
            $this->card->campaignTimelineItems()->findOrFail($this->editingId)->update($data);
            session()->flash('sucesso', 'Evento atualizado.');
        } else {
            $data['order'] = $this->card->campaignTimelineItems()->count();
            $this->card->campaignTimelineItems()->create($data);
            session()->flash('sucesso', 'Evento adicionado à linha do tempo.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        $this->card->campaignTimelineItems()->findOrFail($id)->delete();
        session()->flash('sucesso', 'Evento removido.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->occurred_on = '';
        $this->title = '';
        $this->description = '';
        $this->icon = 'flag';
        $this->editingId = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.campaign.timeline-manager', [
            'items' => $this->card->campaignTimelineItems()->get(),
        ]);
    }
}
