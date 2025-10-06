<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up()
{
    Schema::create('parent_categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    if (!Schema::hasColumn('categories', 'parent_id')) {
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()
                  ->constrained('parent_categories')
                  ->onDelete('cascade');
        });
    }
}

public function down()
{
    Schema::dropIfExists('parent_categories');
}

};
