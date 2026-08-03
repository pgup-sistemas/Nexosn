<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\CardTemplateResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CardTemplateResolverTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithCard(array $cardAttrs = []): User
    {
        $user = User::factory()->create();
        $user->card()->create(array_merge([
            'slug'         => 'titular-' . $user->id,
            'display_name' => 'Titular Teste',
            'is_active'    => true,
        ], $cardAttrs));
        return $user->fresh(['card']);
    }

    public function test_resolve_retorna_view_default_quando_template_nao_definido(): void
    {
        $user = $this->makeUserWithCard();
        $resolved = app(CardTemplateResolver::class)->resolve($user->card);

        $this->assertSame('default', $resolved['key']);
        $this->assertSame('card.show', $resolved['view']);
    }

    public function test_resolve_retorna_view_dark(): void
    {
        $user = $this->makeUserWithCard(['template' => 'dark']);
        $resolved = app(CardTemplateResolver::class)->resolve($user->card);

        $this->assertSame('card.show-dark', $resolved['view']);
    }

    public function test_resolve_cai_para_default_quando_template_desconhecido(): void
    {
        $user = $this->makeUserWithCard(['template' => 'inexistente']);
        $resolved = app(CardTemplateResolver::class)->resolve($user->card);

        $this->assertSame('default', $resolved['key']);
    }

    public function test_pagina_publica_do_cartao_carrega_com_template_default(): void
    {
        $user = $this->makeUserWithCard();

        $response = $this->get('/u/' . $user->card->slug);

        $response->assertOk();
    }

    public function test_pagina_publica_do_cartao_carrega_com_template_dark(): void
    {
        $user = $this->makeUserWithCard(['template' => 'dark']);

        $response = $this->get('/u/' . $user->card->slug);

        $response->assertOk();
    }

    public function test_options_lista_templates_registrados(): void
    {
        $options = app(CardTemplateResolver::class)->options();

        $this->assertArrayHasKey('default', $options);
        $this->assertArrayHasKey('dark', $options);
    }
}
