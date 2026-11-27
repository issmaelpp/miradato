<?php

use App\Enums\PostStatus;
use App\Enums\PostType;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

test('posts use UUIDs, belong to a user, and support soft deletes', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();

    expect(Str::isUuid($post->id))->toBeTrue()
        ->and($post->user->is($user))->toBeTrue()
        ->and($user->posts->contains($post))->toBeTrue()
        ->and($post->type)->toBe(PostType::Noticia)
        ->and($post->status)->toBe(PostStatus::Borrador)
        ->and($post->published_at)->toBeNull();

    $post->delete();

    expect(Post::find($post->id))->toBeNull()
        ->and(Post::withTrashed()->find($post->id))->not->toBeNull();
});

test('post types are stored and cast to their enum cases', function () {
    foreach (PostType::cases() as $type) {
        $post = Post::factory()->create(['type' => $type]);

        expect($post->refresh()->type)->toBe($type);
    }
});

test('post statuses are stored and cast to their enum cases', function () {
    foreach (PostStatus::cases() as $status) {
        $post = Post::factory()->create(['status' => $status]);

        expect($post->refresh()->status)->toBe($status);
    }
});
