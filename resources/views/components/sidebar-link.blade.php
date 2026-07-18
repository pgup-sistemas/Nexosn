@props(['href', 'active' => false, 'icon', 'label'])

<a href="{{ $href }}" wire:navigate
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active
              ? 'bg-white/15 text-white'
              : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
    <i data-lucide="{{ $icon }}" class="w-4 h-4 shrink-0"></i>
    {{ $label }}
</a>
