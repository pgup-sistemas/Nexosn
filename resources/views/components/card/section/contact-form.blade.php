@props(['card', 'purposeOptions' => []])
<x-card.section.collapsible icon="send" title="Enviar mensagem">
    <div class="campaign-contact-form-wrap">
        <livewire:card.contact-form :card="$card" :purposeOptions="$purposeOptions" />
    </div>
</x-card.section.collapsible>
