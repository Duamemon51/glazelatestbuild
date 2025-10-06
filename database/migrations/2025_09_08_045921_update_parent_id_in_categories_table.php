<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // pehle purana foreign key drop karo
            $table->dropForeign(['parent_id']);

            // phir naya foreign key ParentCategory table se link karo
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('parent_categories')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);

            // rollback par wapas categories se hi link ho jaye
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }
};
