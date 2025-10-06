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
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('color_category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code');
            $table->integer('rgb_r')->unsigned();
            $table->integer('rgb_g')->unsigned();
            $table->integer('rgb_b')->unsigned();
            $table->string('closest_association')->nullable();
            $table->text('description')->nullable();
            $table->enum('coloring_system', ['pantone_coated', 'pantone_uncoated', 'hks_k', 'hks_n', 'ral']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colors');
    }
};
