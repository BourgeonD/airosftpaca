<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reported_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['positive', 'negative']);
            $table->string('reason');
            $table->text('comment')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'dismissed'])->default('pending');
            $table->unique(['reporter_id', 'reported_id', 'type']);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->decimal('trust_score', 3, 1)->default(3.0)->after('role');
            $table->string('pseudo')->nullable()->after('name');
            $table->date('birthdate')->nullable()->after('pseudo');
            $table->string('game_style')->nullable()->after('location');
            $table->text('equipment')->nullable()->after('game_style');
            $table->integer('games_played')->default(0)->after('equipment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_reports');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['trust_score','pseudo','birthdate','game_style','equipment','games_played']);
        });
    }
};
