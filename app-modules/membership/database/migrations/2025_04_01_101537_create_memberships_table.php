<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->uuid('group_id');
            $table->uuid('user_id');
            $table->string('role');
            $table->timestamps();
            $table->primary(['group_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
