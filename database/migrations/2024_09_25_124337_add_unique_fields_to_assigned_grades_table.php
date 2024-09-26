<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assigned_grades', function (Blueprint $table) {
            $table->unique(['team_id', 'user_id', 'class_category_id']);
        });
    }

    public function down(): void
    {
        Schema::table('assigned_grades', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'team_id', 'class_category_id']);
        });
    }
};
