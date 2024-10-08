<?php

use App\Models\Group;
use App\Models\GroupRole;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('group_invitations', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Group::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(GroupRole::class)->nullable()->constrained()->nullOnDelete();
            $table->string('email');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_invitations');
    }
};
