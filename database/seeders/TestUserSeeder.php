<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Titular Free com trial Pro ativo
        $user = User::firstOrCreate(
            ['email' => 'teste@card.test'],
            [
                'name'              => 'Joao Teste',
                'slug'              => 'joao-teste',
                'password'          => Hash::make('card@2026'),
                'email_verified_at' => now(),
                'plan'              => 'free',
                'trial_ends_at'     => now()->addDays(14),
            ]
        );

        Card::firstOrCreate(
            ['user_id' => $user->id],
            [
                'slug'           => 'joao-teste',
                'display_name'   => 'Joao Teste',
                'title'          => 'Desenvolvedor',
                'company'        => 'PageUp Sistemas',
                'bio'            => 'Cartao de visita digital de teste.',
                'is_active'      => true,
                'show_watermark' => false,
                'contact_phone'  => '(69) 99999-9999',
                'contact_email'  => 'teste@card.test',
            ]
        );

        // Admin Filament (plano Pro permanente)
        $admin = User::firstOrCreate(
            ['email' => 'admin@pageup.net.br'],
            [
                'name'              => 'Admin PageUp',
                'slug'              => 'admin-pageup',
                'password'          => Hash::make('admin@2026'),
                'email_verified_at' => now(),
                'plan'              => 'pro',
                'plan_expires_at'   => now()->addYears(10),
            ]
        );

        Card::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'slug'           => 'admin-pageup',
                'display_name'   => 'Admin PageUp',
                'is_active'      => true,
                'show_watermark' => false,
            ]
        );

        $this->command->info('Usuarios de teste criados!');
    }
}
