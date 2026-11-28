<?php

use App\Models\User;
use Database\Seeders\AutomatedPublisherSeeder;
use Database\Seeders\DatabaseSeeder;

test('automated publisher seeder reuses the technical account when run repeatedly', function () {
    $seeder = app(AutomatedPublisherSeeder::class);
    $seeder->run();
    $publisher = User::where('email', 'publicador@miradato.local')->firstOrFail();

    $seeder->run();

    $this->assertModelExists($publisher);
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseHas('users', [
        'email' => 'publicador@miradato.local',
        'name' => 'Publicador automático',
    ]);
});

test('database seeding creates the automated publisher account', function () {
    $this->seed(DatabaseSeeder::class);

    $publisher = User::where('email', 'publicador@miradato.local')->firstOrFail();

    $this->assertModelExists($publisher);
});
