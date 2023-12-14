<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['project_list_id']);
            $table->foreign('project_list_id')->references('id')->on('project_lists')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['project_list_id']);
            $table->foreign('project_list_id')->references('id')->on('project_lists');
        });
    }
};
