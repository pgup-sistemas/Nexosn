<?php

namespace App\Livewire\Campaign;

use App\Models\Card;
use Livewire\Component;

class EventManager extends Component
{
    public Card $card;

    public bool $showForm = false;
    public ?int $editingId = null;

    public string $title = '';
    public string $description = '';
    public string $event_date = '';
    public string $event_time = '';
    public string $location = '';
    public string $map_url = '';
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'map_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
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
        $event = $this->card->campaignEvents()->findOrFail($id);
        $this->editingId = $id;
        $this->title = $event->title;
        $this->description = $event->description ?? '';
        $this->event_date = $event->event_date->format('Y-m-d');
        $this->event_time = $event->event_time ? substr($event->event_time, 0, 5) : '';
        $this->location = $event->location ?? '';
        $this->map_url = $event->map_url ?? '';
        $this->is_active = $event->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title' => trim($this->title),
            'description' => trim($this->description) ?: null,
            'event_date' => $this->event_date,
            'event_time' => $this->event_time ?: null,
            'location' => trim($this->location) ?: null,
            'map_url' => trim($this->map_url) ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            $this->card->campaignEvents()->findOrFail($this->editingId)->update($data);
            session()->flash('sucesso', 'Evento atualizado.');
        } else {
            $data['order'] = $this->card->campaignEvents()->count();
            $this->card->campaignEvents()->create($data);
            session()->flash('sucesso', 'Evento criado.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        $this->card->campaignEvents()->findOrFail($id)->delete();
        session()->flash('sucesso', 'Evento removido.');
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
        $this->event_date = '';
        $this->event_time = '';
        $this->location = '';
        $this->map_url = '';
        $this->is_active = true;
        $this->editingId = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.campaign.event-manager', [
            'events' => $this->card->campaignEvents()->get(),
        ]);
    }
}
