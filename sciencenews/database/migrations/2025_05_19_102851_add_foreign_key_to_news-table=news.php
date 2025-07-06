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
        Schema::table('news', function (Blueprint $table) {
        $table->integer('cat_id')->change();
        
        $table->foreign('cat_id')
              ->references('id')
              ->on('categories')
              ->onDelete('cascade');
    });
    }


    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
        $table->dropForeign(['cat_id']);
    });
    }
};
