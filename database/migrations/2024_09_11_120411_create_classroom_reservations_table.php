<?php

use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('classroom_reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Classroom::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Teacher::class)->nullable()->constrained()->nullOnDelete();

            $table->timestamp('booked_from')->nullable();
            $table->timestamp('booked_to')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_reservations');
    }
};
