<?php

namespace App\Models;

use App\Enums\ScrapedPostStatus;
use Database\Factories\ScrapedPostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $source_id
 * @property string|null $source_identifier
 * @property string $source_url
 * @property string $url_hash
 * @property string $title
 * @property string $content
 * @property string|null $excerpt
 * @property string|null $source_author
 * @property array<int, string>|null $source_categories
 * @property array<int, string>|null $image_urls
 * @property Carbon|null $source_published_at
 * @property Carbon|null $source_modified_at
 * @property Carbon $scraped_at
 * @property string $content_hash
 * @property ScrapedPostStatus $status
 * @property string|null $possible_duplicate_of_id
 */
#[Fillable([
    'source_id',
    'source_identifier',
    'source_url',
    'url_hash',
    'title',
    'content',
    'excerpt',
    'source_author',
    'source_categories',
    'image_urls',
    'source_published_at',
    'source_modified_at',
    'scraped_at',
    'content_hash',
    'status',
    'possible_duplicate_of_id',
])]
class ScrapedPost extends Model
{
    /** @use HasFactory<ScrapedPostFactory> */
    use HasFactory, HasUuids;

    /**
     * Get the source that published this article.
     *
     * @return BelongsTo<Source, $this>
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    /**
     * Get the original article that this record may duplicate.
     *
     * @return BelongsTo<ScrapedPost, $this>
     */
    public function possibleDuplicateOf(): BelongsTo
    {
        return $this->belongsTo(ScrapedPost::class, 'possible_duplicate_of_id');
    }

    /**
     * Get scraped articles that may duplicate this record.
     *
     * @return HasMany<ScrapedPost, $this>
     */
    public function possibleDuplicates(): HasMany
    {
        return $this->hasMany(ScrapedPost::class, 'possible_duplicate_of_id');
    }

    /**
     * Get the posts based on this scraped article.
     *
     * @return BelongsToMany<Post, $this>
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_scraped_post')->withTimestamps();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'source_categories' => 'array',
            'image_urls' => 'array',
            'source_published_at' => 'datetime',
            'source_modified_at' => 'datetime',
            'scraped_at' => 'datetime',
            'status' => ScrapedPostStatus::class,
        ];
    }
}
