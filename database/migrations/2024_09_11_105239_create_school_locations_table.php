<?php

use App\Models\City;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_locations', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(City::class)->nullable()->constrained()->nullOnDelete();
            $table->string('address');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_locations');
    }
};
