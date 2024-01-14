<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_lists', function (Blueprint $table) {
            $table->dropForeign(['board_id']);
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('project_lists', function (Blueprint $table) {
            $table->dropForeign(['board_id']);
            $table->foreign('board_id')->references('id')->on('boards');
        });
    }
};
