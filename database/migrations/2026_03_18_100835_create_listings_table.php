<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('listing_category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->enum('condition', ['neuf','tres_bon','bon','acceptable','pour_pieces']);
            $table->enum('status', ['active','sold','closed'])->default('active');
            $table->string('external_url')->nullable();
            $table->string('location')->nullable();
            $table->json('photos')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('listings'); }
};
