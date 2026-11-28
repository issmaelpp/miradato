<?php

namespace App\Models;

use App\Enums\SourceType;
use Database\Factories\SourceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $domain
 * @property SourceType $type
 * @property bool $is_active
 */
#[Fillable(['name', 'slug', 'domain', 'type', 'is_active'])]
class Source extends Model
{
    /** @use HasFactory<SourceFactory> */
    use HasFactory, HasUuids;

    /**
     * Get the scraped articles from this source.
     *
     * @return HasMany<ScrapedPost, $this>
     */
    public function scrapedPosts(): HasMany
    {
        return $this->hasMany(ScrapedPost::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => SourceType::class,
            'is_active' => 'boolean',
        ];
    }
}
