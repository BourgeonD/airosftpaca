<?php
// database/migrations/2024_01_01_000006_create_events_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('squad_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('rules')->nullable();
            $table->decimal('paf_price', 8, 2)->nullable()->comment('Prix de la PAF en euros');
            $table->string('location_name');
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->dateTime('event_date');
            $table->integer('max_participants')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled', 'finished'])->default('published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
