<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AutomatedPublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'publicador@miradato.local'],
            [
                'name' => 'Publicador automático',
                'password' => Str::random(64),
                'email_verified_at' => now(),
            ],
        );
    }
}
