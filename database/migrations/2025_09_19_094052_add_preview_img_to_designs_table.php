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
    Schema::table('designs', function (Blueprint $table) {
        $table->string('preview_img')->nullable()->after('right_image');
    });
}

public function down(): void
{
    Schema::table('designs', function (Blueprint $table) {
        $table->dropColumn('preview_img');
    });
}

};
