<?php

use App\Models\ClassCategory;
use App\Models\Teacher;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assigned_grades', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Team::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Teacher::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(ClassCategory::class)->nullable()->constrained()->nullOnDelete();

            $table->string('grade', 8)->default('02');
            $table->string('comment');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assigned_grades');
    }
};
