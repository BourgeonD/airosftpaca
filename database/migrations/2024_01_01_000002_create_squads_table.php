<?php
// database/migrations/2024_01_01_000002_create_squads_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('squads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('history')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('city')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->foreignId('leader_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_recruiting')->default(true);
            $table->integer('min_age')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('squads');
    }
};
