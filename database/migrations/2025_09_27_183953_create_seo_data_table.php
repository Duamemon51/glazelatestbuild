<?php

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
        Schema::create('seo_data', function (Blueprint $table) {
            $table->id();
            $table->morphs('seoable'); // Polymorphic relationship
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            $table->string('twitter_card')->default('summary');
            $table->json('structured_data')->nullable(); // JSON-LD structured data
            $table->json('seo_score_data')->nullable(); // SEO analysis results
            $table->integer('seo_score')->default(0); // Overall SEO score 0-100
            $table->json('seo_issues')->nullable(); // Array of SEO issues found
            $table->json('seo_recommendations')->nullable(); // Array of recommendations
            $table->boolean('noindex')->default(false);
            $table->boolean('nofollow')->default(false);
            $table->timestamps();

            // Index already created by morphs() method above
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_data');
    }
};
