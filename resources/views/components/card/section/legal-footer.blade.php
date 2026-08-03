@props(['card'])
@php $profile = $card->campaignProfile; @endphp
<div class="campaign-legal-footer">
    @if ($profile?->legal_responsible_name)
        <p>Responsável pelo conteúdo: {{ $profile->legal_responsible_name }}@if ($profile->legal_responsible_document) — {{ $profile->legal_responsible_document }} @endif</p>
    @endif
    <p>O conteúdo deste cartão é de responsabilidade exclusiva do titular. Em eleições oficiais, observe a legislação eleitoral e as normas vigentes do TSE.</p>
</div>
