<?php

use App\Models\Lesson;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homework', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Lesson::class)->nullable()->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->longText('description');
            $table->timestamp('due_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homework');
    }
};
