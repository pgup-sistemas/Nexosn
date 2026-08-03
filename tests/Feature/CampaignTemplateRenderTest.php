<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CampaignTemplateRenderTest extends TestCase
{
    use RefreshDatabase;

    private function makeFullCampaignCard(string $template): User
    {
        $user = User::factory()->create([
            'plan' => 'pro',
            'plan_expires_at' => now()->addMonth(),
        ]);
        $user->card()->create([
            'slug' => 'campanha-' . $user->id,
            'display_name' => 'Titular Teste',
            'is_active' => true,
            'template' => $template,
        ]);
        $card = $user->fresh(['card'])->card;

        $card->campaignProfile()->create([
            'campaign_name' => 'Chapa Renovação',
            'role_title' => 'Presidente',
            'ballot_number' => '13',
            'slogan' => 'Juntos somos mais fortes',
            'countdown_target_at' => now()->addDays(10),
        ]);
        $card->campaignProposals()->create(['title' => 'Mais transparência', 'order' => 0]);
        $card->campaignNews()->create(['title' => 'Lançamento', 'published_at' => now(), 'order' => 0]);
        $card->campaignTimelineItems()->create(['occurred_on' => '2020-01-01', 'title' => 'Fundação', 'order' => 0]);
        $card->campaignTeamMembers()->create(['name' => 'Maria Silva', 'role' => 'Vice', 'order' => 0]);
        $card->campaignEvents()->create(['title' => 'Comitê', 'event_date' => now()->addDays(3), 'order' => 0]);
        $card->files()->create(['label' => 'Plano', 'category' => 'management_plan', 'file_path' => 'x.pdf', 'order' => 0]);

        return $user->fresh(['card']);
    }

    public function test_template_hero_renderiza_com_dados_completos(): void
    {
        $user = $this->makeFullCampaignCard('campaign-hero');

        $response = $this->get('/u/' . $user->card->slug);

        $response->assertOk();
        $response->assertSee('Chapa Renovação');
        $response->assertSee('Mais transparência');
        $response->assertSee('Comitê');
        $response->assertSee('Maria Silva');
    }

    public function test_template_institucional_renderiza_com_dados_completos(): void
    {
        $user = $this->makeFullCampaignCard('campaign-institucional');

        $response = $this->get('/u/' . $user->card->slug);

        $response->assertOk();
        $response->assertSee('Chapa Renovação');
        $response->assertSee('Fundação');
        $response->assertSee('Lançamento');
    }

    #[DataProvider('remainingTemplatesProvider')]
    public function test_demais_templates_de_campanha_renderizam(string $template): void
    {
        $user = $this->makeFullCampaignCard($template);

        $response = $this->get('/u/' . $user->card->slug);

        $response->assertOk();
        $response->assertSee('Chapa Renovação');
    }

    public static function remainingTemplatesProvider(): array
    {
        return [
            ['campaign-retrato'],
            ['campaign-banner'],
            ['campaign-minimalista'],
            ['campaign-chapa'],
            ['campaign-moderno'],
        ];
    }

    public function test_template_campanha_sem_perfil_nao_quebra(): void
    {
        $user = User::factory()->create();
        $user->card()->create([
            'slug' => 'sem-perfil-' . $user->id,
            'display_name' => 'Sem Perfil',
            'is_active' => true,
            'template' => 'campaign-hero',
        ]);

        $response = $this->get('/u/' . $user->card->slug);

        $response->assertOk();
    }
}
