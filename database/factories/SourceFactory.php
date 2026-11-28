<?php

namespace Database\Factories;

use App\Enums\SourceType;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Source>
 */
class SourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company();
        $domain = fake()->unique()->domainName();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'domain' => $domain,
            'type' => fake()->randomElement(SourceType::cases()),
            'is_active' => true,
        ];
    }
}
