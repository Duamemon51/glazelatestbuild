<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('name');
            $table->text('description')->nullable();

            // Relations
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            // Attributes
            $table->string('type')->nullable();   // e.g. "Jersey", "Shoes"
            $table->enum('gender', ['Men', 'Women', 'Kids'])->nullable();

            // Pricing
            $table->decimal('price', 10, 2)->default(0);

            // Media
            $table->string('image')->nullable();       // main image
            $table->string('model_3d')->nullable();    // local path to 3D model
            $table->string('model_3d_url')->nullable(); // external 3D viewer URL

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
