<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Enums\PostType;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(3, true),
            'excerpt' => fake()->optional()->paragraph(),
            'type' => PostType::Noticia,
            'status' => PostStatus::Borrador,
            'published_at' => null,
        ];
    }
}
