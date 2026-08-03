@props(['card'])
@php $files = $card->files; @endphp
@if ($files->isNotEmpty())
<x-card.section.collapsible icon="folder-down" title="Materiais e Documentos">
    <div class="campaign-file-list">
        @foreach ($files as $file)
            <a href="{{ route('card.file.download', ['card' => $card->slug, 'file' => $file->id]) }}" target="_blank" class="campaign-file-item">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span>{{ $file->label }}</span>
                <i data-lucide="download" class="w-4 h-4 campaign-file-download-icon"></i>
            </a>
        @endforeach
    </div>
</x-card.section.collapsible>
@endif
