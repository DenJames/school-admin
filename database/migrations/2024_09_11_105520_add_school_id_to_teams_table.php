<?php

use App\Models\School;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->foreignIdFor(School::class)->nullable()->after('user_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            //
        });
    }
};
