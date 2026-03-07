<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Si la table n'existe pas encore, la créer complètement
        if (!Schema::hasTable('squad_invitations')) {
            Schema::create('squad_invitations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('squad_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
                $table->enum('status', ['pending','accepted','declined'])->default('pending');
                $table->string('token')->unique()->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        } else {
            // Ajouter event_id si la table existe déjà
            if (!Schema::hasColumn('squad_invitations', 'event_id')) {
                Schema::table('squad_invitations', function (Blueprint $table) {
                    $table->foreignId('event_id')->nullable()->after('user_id')
                          ->constrained()->onDelete('cascade');
                });
            }
        }
    }

    public function down(): void
    {
        Schema::table('squad_invitations', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }
};
