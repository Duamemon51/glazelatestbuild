<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parent_categories', function (Blueprint $table) {
            $table->string('home_image')->nullable()->after('image');
            $table->string('category_image')->nullable()->after('home_image');
        });
    }

    public function down(): void
    {
        Schema::table('parent_categories', function (Blueprint $table) {
            $table->dropColumn(['home_image', 'category_image']);
        });
    }
};
