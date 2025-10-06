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
       Schema::create('designs', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('front_image')->nullable();
    $table->string('back_image')->nullable();
    $table->string('left_image')->nullable();
    $table->string('right_image')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};
