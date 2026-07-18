<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plans')->upsert([
            [
                'name'        => 'Free',
                'slug'        => 'free',
                'price'       => 0,
                'description' => 'Cartão digital gratuito com marca d\'água Card.',
                'features'    => json_encode([
                    'links'        => 5,
                    'photos'       => 3,
                    'custom_colors' => false,
                    'agenda'       => false,
                    'watermark'    => true,
                ]),
            ],
            [
                'name'        => 'Pro',
                'slug'        => 'pro',
                'price'       => 2990,
                'description' => 'Cartão profissional sem marca d\'água, com agenda e cores personalizadas.',
                'features'    => json_encode([
                    'links'        => -1,
                    'photos'       => 30,
                    'custom_colors' => true,
                    'agenda'       => true,
                    'watermark'    => false,
                ]),
            ],
        ], ['slug'], ['name', 'price', 'description', 'features']);
    }
}
