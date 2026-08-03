<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * SQLite (usado nos testes) não impõe limite de VARCHAR, então um
 * teste via Eloquent/RefreshDatabase não detectaria truncamento real
 * de MySQL. Este teste inspeciona a config diretamente, sem bootstrap
 * do Laravel, para garantir que a coluna comporta a maior chave registrada.
 */
class CardTemplateColumnLengthTest extends TestCase
{
    public function test_coluna_template_comporta_a_maior_chave_registrada(): void
    {
        $templates = require dirname(__DIR__, 2) . '/config/card_templates.php';
        $longestKey = collect(array_keys($templates))->map('strlen')->max();

        $this->assertLessThanOrEqual(
            40,
            $longestKey,
            'Uma chave de template excede o tamanho da coluna cards.template (varchar 40). Gere uma migration para alargar a coluna.'
        );
    }
}
