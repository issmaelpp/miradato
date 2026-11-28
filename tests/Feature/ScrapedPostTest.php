<?php

use App\Enums\ScrapedPostStatus;
use App\Enums\SourceType;
use App\Models\Post;
use App\Models\ScrapedPost;
use App\Models\Source;
use Illuminate\Database\QueryException;

test('scraped articles retain normalized metadata and their source and post relationships', function () {
    $source = Source::factory()->create(['type' => SourceType::Oficial]);
    $article = ScrapedPost::factory()->for($source)->create([
        'source_categories' => ['municipal', 'educacion'],
        'image_urls' => ['https://example.test/noticia.jpg'],
        'status' => ScrapedPostStatus::Procesado,
    ]);
    $post = Post::factory()->create();
    $post->scrapedPosts()->attach($article);
    $possibleDuplicate = ScrapedPost::factory()->for($source)->create([
        'possible_duplicate_of_id' => $article->id,
    ]);

    $article->refresh();

    expect($source->refresh()->type)->toBe(SourceType::Oficial)
        ->and($article->source->is($source))->toBeTrue()
        ->and($source->scrapedPosts->contains($article))->toBeTrue()
        ->and($article->source_categories)->toBe(['municipal', 'educacion'])
        ->and($article->image_urls)->toBe(['https://example.test/noticia.jpg'])
        ->and($article->status)->toBe(ScrapedPostStatus::Procesado)
        ->and($article->posts->contains($post))->toBeTrue()
        ->and($post->scrapedPosts->contains($article))->toBeTrue()
        ->and($possibleDuplicate->possibleDuplicateOf->is($article))->toBeTrue()
        ->and($article->possibleDuplicates->contains($possibleDuplicate))->toBeTrue();
});

test('scraped article URL uniqueness is scoped to its source, while matching content hashes can repeat', function () {
    $source = Source::factory()->create();
    $otherSource = Source::factory()->create();
    $sourceUrl = 'https://news.example.test/noticia';
    $urlHash = hash('sha256', $sourceUrl);
    $contentHash = hash('sha256', 'normalized article text');

    $firstArticle = ScrapedPost::factory()->for($source)->create([
        'source_url' => $sourceUrl,
        'url_hash' => $urlHash,
        'content_hash' => $contentHash,
    ]);
    $sameUrlFromAnotherSource = ScrapedPost::factory()->for($otherSource)->create([
        'source_url' => $sourceUrl,
        'url_hash' => $urlHash,
        'content_hash' => $contentHash,
    ]);

    $this->assertModelExists($firstArticle);
    $this->assertModelExists($sameUrlFromAnotherSource);

    expect(fn () => ScrapedPost::factory()->for($source)->create([
        'source_url' => $sourceUrl,
        'url_hash' => $urlHash,
        'content_hash' => $contentHash,
    ]))->toThrow(QueryException::class);
});
