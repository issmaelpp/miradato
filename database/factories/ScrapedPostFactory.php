<?php

namespace Database\Factories;

use App\Enums\ScrapedPostStatus;
use App\Models\ScrapedPost;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ScrapedPost>
 */
class ScrapedPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();
        $sourceUrl = fake()->unique()->url();
        $content = fake()->paragraph();

        return [
            'source_id' => Source::factory(),
            'source_identifier' => fake()->optional()->uuid(),
            'source_url' => $sourceUrl,
            'url_hash' => hash('sha256', $sourceUrl),
            'title' => $title,
            'content' => $content,
            'excerpt' => fake()->optional()->sentence(),
            'source_author' => fake()->optional()->name(),
            'source_categories' => [fake()->word()],
            'image_urls' => ['https://'.fake()->domainName().'/'.Str::random(12).'.jpg'],
            'source_published_at' => fake()->optional()->dateTime(),
            'source_modified_at' => fake()->optional()->dateTime(),
            'scraped_at' => now(),
            'content_hash' => hash('sha256', $content),
            'status' => ScrapedPostStatus::Pendiente,
        ];
    }
}
