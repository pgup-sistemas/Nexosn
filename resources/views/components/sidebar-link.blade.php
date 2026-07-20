@props(['href', 'active' => false, 'icon', 'label', 'badge' => null])

<a href="{{ $href }}" wire:navigate
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
          {{ $active
              ? 'bg-white/15 text-white'
              : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
    <i data-lucide="{{ $icon }}" class="w-4 h-4 shrink-0"></i>
    <span class="flex-1">{{ $label }}</span>
    @if ($badge)
    <span class="ml-auto min-w-[18px] h-[18px] flex items-center justify-center rounded-full text-[10px] font-bold text-white px-1"
          style="background-color: #D62828;">
        {{ $badge > 99 ? '99+' : $badge }}
    </span>
    @endif
</a>
