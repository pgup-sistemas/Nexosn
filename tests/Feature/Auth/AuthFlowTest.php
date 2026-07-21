<?php

namespace Tests\Feature\Auth;

use App\Models\Card;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_cadastro_cria_usuario_com_slug(): void
    {
        $component = Volt::test('pages.auth.register')
            ->set('name', 'João Silva')
            ->set('slug', 'joao-silva')
            ->set('email', 'joao@teste.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('terms', true);

        $component->call('register');

        $component->assertHasNoErrors()->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'joao@teste.com',
            'slug'  => 'joao-silva',
            'plan'  => 'free',
        ]);
    }

    public function test_slug_reservado_e_rejeitado(): void
    {
        $component = Volt::test('pages.auth.register')
            ->set('name', 'Admin User')
            ->set('slug', 'admin')
            ->set('email', 'admin@teste.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123');

        $component->call('register')->assertHasErrors(['slug']);
    }

    public function test_slug_duplicado_e_rejeitado(): void
    {
        User::factory()->create(['slug' => 'slug-existente']);

        $component = Volt::test('pages.auth.register')
            ->set('name', 'Outro User')
            ->set('slug', 'slug-existente')
            ->set('email', 'outro@teste.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123');

        $component->call('register')->assertHasErrors(['slug']);
    }

    public function test_verificacao_ativa_trial_e_cria_cartao(): void
    {
        $user = User::factory()->unverified()->create([
            'slug' => 'teste-user',
            'plan' => 'free',
        ]);

        $this->assertNull($user->trial_ends_at);
        $this->assertDatabaseMissing('cards', ['user_id' => $user->id]);

        event(new Verified($user));

        $user->refresh();

        $this->assertEquals('pro', $user->plan);
        $this->assertNotNull($user->trial_ends_at);
        $this->assertTrue($user->trial_ends_at->isFuture());
        $this->assertDatabaseHas('cards', [
            'user_id' => $user->id,
            'slug'    => 'teste-user',
        ]);
    }

    public function test_trial_nao_e_ativado_duas_vezes(): void
    {
        $user = User::factory()->create([
            'slug'          => 'usuario-trial',
            'plan'          => 'pro',
            'trial_ends_at' => now()->addDays(10),
        ]);

        $trialOriginal = $user->trial_ends_at;

        event(new Verified($user));

        $user->refresh();
        $this->assertEquals($trialOriginal->timestamp, $user->trial_ends_at->timestamp);
    }

    public function test_cartao_publico_acessivel_pelo_slug(): void
    {
        $user = User::factory()->create(['slug' => 'publico-teste']);
        Card::create([
            'user_id'      => $user->id,
            'slug'         => 'publico-teste',
            'display_name' => 'Usuário Público',
            'is_active'    => true,
            'show_watermark' => true,
        ]);

        $response = $this->get('/u/publico-teste');
        $response->assertStatus(200);
    }

    public function test_slug_com_caracteres_invalidos_e_rejeitado(): void
    {
        $component = Volt::test('pages.auth.register')
            ->set('name', 'Test User')
            ->set('slug', 'slug inválido!')
            ->set('email', 'test@teste.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123');

        $component->call('register')->assertHasErrors(['slug']);
    }
}
