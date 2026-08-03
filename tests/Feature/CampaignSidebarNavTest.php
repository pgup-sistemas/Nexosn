<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CampaignSidebarNavTest extends TestCase
{
    use RefreshDatabase;

    private function makeUserWithCard(string $template): User
    {
        $user = User::factory()->create([
            'plan' => 'pro',
            'plan_expires_at' => now()->addMonth(),
        ]);
        $user->card()->create([
            'slug' => 'titular-' . $user->id,
            'display_name' => 'Titular Teste',
            'is_active' => true,
            'template' => $template,
        ]);
        return $user->fresh(['card']);
    }

    public function test_menu_de_campanha_aparece_quando_template_e_de_campanha(): void
    {
        $user = $this->makeUserWithCard('campaign-hero');

        $response = $this->actingAs($user)->get(route('dashboard.card'));

        $response->assertOk();
        $response->assertSee('Campanha'); // subtítulo separando a seção exclusiva do template
        $response->assertSee('Perfil de Campanha');
        $response->assertSee('Propostas');
        $response->assertSee('Notícias');
        $response->assertSee('Linha do Tempo');
        $response->assertSee('Equipe / Chapa');
        $response->assertSee('Agenda de Eventos');
    }

    public function test_menu_de_campanha_nao_aparece_com_template_padrao(): void
    {
        $user = $this->makeUserWithCard('default');

        $response = $this->actingAs($user)->get(route('dashboard.card'));

        $response->assertOk();
        $response->assertDontSee('Perfil de Campanha');
    }

    public function test_menu_e_orientado_a_dados_novo_modulo_aparece_sem_alterar_a_view(): void
    {
        // Prova que o menu lateral não está hardcoded para "campaign" —
        // registrar um módulo fictício em config já basta para ele aparecer.
        Route::get('/dashboard/fake-modulo/tela-fake', fn () => 'ok')->name('dashboard.fake-modulo.tela');
        // Rotas registradas em tempo de execução (fora do boot normal do routes/web.php)
        // não entram na tabela de lookup por nome automaticamente — só nesta situação de
        // teste. Em produção as rotas nascem no boot e isso não é necessário.
        app('router')->getRoutes()->refreshNameLookups();

        Config::set('card_templates.template-fake', [
            'label' => 'Template Fake',
            'view' => 'card.show',
            'requires_profile' => 'modulo-fake',
            'min_plan' => 'pro',
        ]);
        Config::set('dashboard_nav_modules.modulo-fake', [
            'items' => [
                ['route' => 'dashboard.fake-modulo.tela', 'icon' => 'flask', 'label' => 'Tela Fictícia'],
            ],
        ]);

        $user = $this->makeUserWithCard('template-fake');

        $response = $this->actingAs($user)->get(route('dashboard.card'));

        $response->assertOk();
        $response->assertSee('Tela Fictícia');
    }
}
