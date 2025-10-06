<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_types', function (Blueprint $table) {
            // Add subcategory_id column if it doesn't exist
            if (!Schema::hasColumn('product_types', 'subcategory_id')) {
                $table->unsignedBigInteger('subcategory_id')->nullable()->after('id');
            }

            // Add foreign key
            $table->foreign('subcategory_id')
                  ->references('id')
                  ->on('subcategories')
                  ->onDelete('cascade');

            // Drop old parent_category_id column if exists
            if (Schema::hasColumn('product_types', 'parent_category_id')) {
                $table->dropForeign(['parent_category_id']);
                $table->dropColumn('parent_category_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_types', function (Blueprint $table) {
            // Drop foreign key & column if exists
            if (Schema::hasColumn('product_types', 'subcategory_id')) {
                $table->dropForeign(['subcategory_id']);
                $table->dropColumn('subcategory_id');
            }

            // Restore old column
            if (!Schema::hasColumn('product_types', 'parent_category_id')) {
                $table->unsignedBigInteger('parent_category_id')->after('id');
                $table->foreign('parent_category_id')
                      ->references('id')
                      ->on('parent_categories')
                      ->onDelete('cascade');
            }
        });
    }
};
