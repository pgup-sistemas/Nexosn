<?php

namespace App\Livewire\Card;

use App\Models\Card;
use App\Services\PlanService;
use App\Services\SocialLinkService;
use Livewire\Attributes\On;
use Livewire\Component;

class LinkManager extends Component
{
    public ?Card $card = null;
    public bool $showForm = false;
    public string $newUrl   = '';
    public string $newLabel = '';
    public string $newIcon  = 'link';
    public string $newType  = 'custom';

    public function mount(): void
    {
        $this->card = auth()->user()->card;
    }

    public function updatedNewUrl(string $value): void
    {
        if (empty($value)) return;

        $service = app(SocialLinkService::class);
        $this->newIcon = $service->detectIcon($value);
        $this->newType = $service->detectType($value);

        if (empty($this->newLabel)) {
            $host = parse_url($value, PHP_URL_HOST) ?? '';
            $this->newLabel = ucfirst(str_replace(['www.', '.com', '.net', '.br'], '', $host));
        }
    }

    public function addLink(): void
    {
        if (!$this->card) return;

        $user = auth()->user();
        $currentCount = $this->card->links()->count();

        $planService = app(PlanService::class);
        if (!$planService->withinLimit($user, 'links', $currentCount)) {
            $this->addError('limit', 'Você atingiu o limite de 5 links no plano Free. Faça upgrade para adicionar mais.');
            return;
        }

        $this->validate([
            'newUrl'   => ['required', 'url', 'max:500'],
            'newLabel' => ['required', 'string', 'max:60'],
        ], [
            'newUrl.required'   => 'Informe a URL do link.',
            'newUrl.url'        => 'A URL deve ser válida (ex: https://...).',
            'newLabel.required' => 'Informe um nome para o link.',
        ]);

        $maxOrder = $this->card->links()->max('order') ?? 0;

        $this->card->links()->create([
            'type'     => $this->newType,
            'label'    => $this->newLabel,
            'url'      => $this->newUrl,
            'icon'     => $this->newIcon,
            'is_active'=> true,
            'order'    => $maxOrder + 1,
        ]);

        $this->reset(['newUrl', 'newLabel', 'newIcon', 'newType', 'showForm']);
        $this->resetErrorBag();
    }

    public function toggleLink(int $id): void
    {
        $link = $this->card->links()->findOrFail($id);
        $link->update(['is_active' => !$link->is_active]);
    }

    public function deleteLink(int $id): void
    {
        $this->card->links()->findOrFail($id)->delete();
    }

    #[On('reorder-links')]
    public function reorder(array $order): void
    {
        foreach ($order as $index => $id) {
            $this->card->links()->where('id', (int) $id)->update(['order' => $index]);
        }
    }

    public function render()
    {
        $links = $this->card?->links()->orderBy('order')->get() ?? collect();
        $count = $links->count();
        $isPro = auth()->user()->isPro() || auth()->user()->isOnTrial();

        return view('livewire.card.link-manager', compact('links', 'count', 'isPro'));
    }
}
