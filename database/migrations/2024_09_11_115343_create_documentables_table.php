<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documentables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();

            $table->string('documentable_type');
            $table->unsignedBigInteger('documentable_id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentables');
    }
};
