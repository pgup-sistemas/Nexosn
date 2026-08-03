@props(['card'])
@php $files = $card->files; @endphp
@if ($files->isNotEmpty())
<div class="cs">
    <h2 class="cs-title"><i data-lucide="folder-down" class="w-4 h-4"></i> Materiais e Documentos</h2>
    <div class="campaign-file-list">
        @foreach ($files as $file)
            <a href="{{ route('card.file.download', ['card' => $card->slug, 'file' => $file->id]) }}" target="_blank" class="campaign-file-item">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span>{{ $file->label }}</span>
                <i data-lucide="download" class="w-4 h-4 campaign-file-download-icon"></i>
            </a>
        @endforeach
    </div>
</div>
@endif
