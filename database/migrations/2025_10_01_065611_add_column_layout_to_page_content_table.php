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
        Schema::table('page_content', function (Blueprint $table) {
            $table->string('layout_type')->default('single')->after('section_type'); // single, two_column, three_column
            $table->longText('column_1_content')->nullable()->after('content');
            $table->longText('column_2_content')->nullable()->after('column_1_content');
            $table->longText('column_3_content')->nullable()->after('column_2_content');
            $table->json('column_settings')->nullable()->after('settings'); // Column-specific settings like widths
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_content', function (Blueprint $table) {
            $table->dropColumn(['layout_type', 'column_1_content', 'column_2_content', 'column_3_content', 'column_settings']);
        });
    }
};
