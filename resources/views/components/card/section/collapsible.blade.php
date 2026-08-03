@props(['icon', 'title'])
<div class="cs cs-collapsible">
    <button type="button" class="cs-title cs-toggle" aria-expanded="true">
        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
        <span>{{ $title }}</span>
        <i data-lucide="chevron-down" class="cs-chevron w-4 h-4"></i>
    </button>
    <div class="cs-body">
        {{ $slot }}
    </div>
</div>
