<?php

use App\Enums\ScrapedPostStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scraped_posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('source_id')->constrained()->restrictOnDelete();
            $table->string('source_identifier')->nullable();
            $table->text('source_url');
            $table->char('url_hash', 64);
            $table->string('title');
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->string('source_author')->nullable();
            $table->json('source_categories')->nullable();
            $table->json('image_urls')->nullable();
            $table->timestamp('source_published_at')->nullable();
            $table->timestamp('source_modified_at')->nullable();
            $table->timestamp('scraped_at')->useCurrent();
            $table->char('content_hash', 64);
            $table->string('status')->default(ScrapedPostStatus::Pendiente->value);
            $table->foreignUuid('possible_duplicate_of_id')
                ->nullable()
                ->constrained('scraped_posts')
                ->nullOnDelete();
            $table->timestamps();

            $table->unique(['source_id', 'url_hash']);
            $table->index(['source_id', 'status']);
            $table->index('content_hash');
            $table->index('possible_duplicate_of_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scraped_posts');
    }
};
