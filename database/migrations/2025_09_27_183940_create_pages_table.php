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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->string('status')->default('draft'); // draft, published, scheduled, trashed
            $table->string('visibility')->default('public'); // public, private, password
            $table->string('password')->nullable();
            $table->string('template')->default('default'); // page template
            $table->string('author_type')->default('user'); // user, system
            $table->unsignedBigInteger('author_id');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->json('featured_image')->nullable(); // Store image data
            $table->json('settings')->nullable(); // Page-specific settings
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
