<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('group_invitations', function (Blueprint $table) {
            $table->string('token')->unique()->after('group_role_id');
        });
    }

    public function down(): void
    {
        Schema::table('group_invitations', function (Blueprint $table) {
            //
        });
    }
};
