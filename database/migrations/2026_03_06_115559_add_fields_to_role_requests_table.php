<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('role_requests', function (Blueprint $table) {
            $table->text('description')->nullable()->after('message');
            $table->string('website')->nullable()->after('description');
            $table->string('facebook')->nullable()->after('website');
            $table->string('instagram')->nullable()->after('facebook');
            $table->boolean('is_recruiting')->default(true)->after('instagram');
            $table->integer('min_age')->nullable()->after('is_recruiting');
        });
    }

    public function down(): void
    {
        Schema::table('role_requests', function (Blueprint $table) {
            $table->dropColumn(['description','website','facebook','instagram','is_recruiting','min_age']);
        });
    }
};
