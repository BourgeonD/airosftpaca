<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('squad_invitations', function (Blueprint $table) {
            $table->string('token')->nullable()->change();
            $table->timestamp('expires_at')->nullable()->change();
        });
    }

    public function down(): void {}
};
