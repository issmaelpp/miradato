<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Enums\PostType;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $user_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string|null $excerpt
 * @property PostType $type
 * @property PostStatus $status
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['user_id', 'title', 'slug', 'content', 'excerpt', 'type', 'status', 'published_at'])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Get the post's author.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the scraped articles used as sources for this post.
     *
     * @return BelongsToMany<ScrapedPost, $this>
     */
    public function scrapedPosts(): BelongsToMany
    {
        return $this->belongsToMany(ScrapedPost::class, 'post_scraped_post')->withTimestamps();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'type' => PostType::class,
            'status' => PostStatus::class,
        ];
    }
}
