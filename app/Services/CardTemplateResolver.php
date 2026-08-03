<?php

namespace App\Services;

use App\Models\Card;
use RuntimeException;

class CardTemplateResolver
{
    /**
     * Resolve a chave de template salva no Card para o registro em
     * config/card_templates.php, caindo para 'default' se a chave for
     * desconhecida ou inativa (ex: template removido do registro).
     */
    public function resolve(Card $card): array
    {
        $key = $card->template ?: 'default';
        $templates = config('card_templates', []);

        if (! isset($templates[$key])) {
            $key = 'default';
        }

        if (! isset($templates[$key])) {
            throw new RuntimeException("Template padrão 'default' não está registrado em config/card_templates.php.");
        }

        return array_merge(['key' => $key], $templates[$key]);
    }

    public function view(Card $card): string
    {
        return $this->resolve($card)['view'];
    }

    /** Lista para popular selects (admin e editor do titular). */
    public function options(): array
    {
        return collect(config('card_templates', []))
            ->map(fn (array $tpl) => $tpl['label'])
            ->all();
    }
}
