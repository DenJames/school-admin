<?php

use App\Models\ClassCategory;
use App\Models\ClassroomReservation;
use App\Models\Teacher;
use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Team::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ClassCategory::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(ClassroomReservation::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Teacher::class)->nullable()->constrained()->nullOnDelete();

            $table->string('name', 255);
            $table->unsignedInteger('duration');
            $table->timestamp('starts_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
