<?php

namespace App\Livewire\Card;

use App\Models\Card;
use App\Models\CardService;
use App\Services\PlanService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ServiceManager extends Component
{
    public Card $card;

    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name        = '';
    public string $description = '';
    public string $price       = '';
    public string $lucide_icon = 'tag';
    public bool   $is_active   = true;

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:60',
            'description' => 'nullable|string|max:160',
            'price'       => 'required|numeric|min:0.01|max:99999.99',
            'lucide_icon' => 'required|string|max:40',
            'is_active'   => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required'  => 'Nome do serviço é obrigatório.',
            'price.required' => 'Valor é obrigatório.',
            'price.numeric'  => 'Valor inválido.',
            'price.min'      => 'Valor mínimo: R$ 0,01.',
        ];
    }

    public function mount(Card $card): void
    {
        $this->card = $card;
    }

    public function canAddService(): bool
    {
        $user  = $this->card->user ?? Auth::user();
        $count = $this->card->services()->count();
        return app(PlanService::class)->withinLimit($user, 'services', $count);
    }

    public function startCreate(): void
    {
        if (! $this->canAddService()) {
            session()->flash('erro', 'Limite de 3 serviços atingido no plano Free. Faça upgrade para Pro e cadastre serviços ilimitados.');
            return;
        }

        $this->resetForm();
        $this->showForm  = true;
        $this->editingId = null;
    }

    public function startEdit(int $id): void
    {
        $service = $this->card->services()->findOrFail($id);
        $this->editingId   = $id;
        $this->name        = $service->name;
        $this->description = $service->description ?? '';
        $this->price       = number_format((float) $service->price, 2, '.', '');
        $this->lucide_icon = $service->lucide_icon;
        $this->is_active   = $service->is_active;
        $this->showForm    = true;
    }

    public function save(): void
    {
        $this->validate();

        if (! $this->editingId && ! $this->canAddService()) {
            session()->flash('erro', 'Limite do plano Free atingido.');
            $this->showForm = false;
            return;
        }

        $data = [
            'name'        => trim($this->name),
            'description' => trim($this->description) ?: null,
            'price'       => (float) $this->price,
            'lucide_icon' => $this->lucide_icon,
            'is_active'   => $this->is_active,
        ];

        if ($this->editingId) {
            $this->card->services()->findOrFail($this->editingId)->update($data);
            session()->flash('sucesso', 'Serviço atualizado.');
        } else {
            $data['sort_order'] = $this->card->services()->count();
            $this->card->services()->create($data);
            session()->flash('sucesso', 'Serviço criado.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function toggleActive(int $id): void
    {
        $svc = $this->card->services()->findOrFail($id);
        $svc->update(['is_active' => !$svc->is_active]);
    }

    public function delete(int $id): void
    {
        $this->card->services()->findOrFail($id)->delete();
        session()->flash('sucesso', 'Serviço removido.');
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->name        = '';
        $this->description = '';
        $this->price       = '';
        $this->lucide_icon = 'tag';
        $this->is_active   = true;
        $this->editingId   = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.card.service-manager', [
            'services' => $this->card->services()->get(),
        ]);
    }
}
