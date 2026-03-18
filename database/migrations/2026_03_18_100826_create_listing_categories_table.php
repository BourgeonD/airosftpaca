<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('listing_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->default('📦');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Catégories de base
        $cats = [
            ['name'=>'Répliques AEG',         'slug'=>'aeg',          'icon'=>'🔫', 'description'=>'Répliques électriques', 'order'=>1],
            ['name'=>'Répliques GBB',          'slug'=>'gbb',          'icon'=>'🔫', 'description'=>'Répliques gaz blowback', 'order'=>2],
            ['name'=>'Répliques Sniper',       'slug'=>'sniper',       'icon'=>'🎯', 'description'=>'Répliques sniper et précision', 'order'=>3],
            ['name'=>'Répliques Shotgun',      'slug'=>'shotgun',      'icon'=>'🔫', 'description'=>'Répliques shotgun', 'order'=>4],
            ['name'=>'Équipement & Tenue',     'slug'=>'equipement',   'icon'=>'🪖', 'description'=>'Tenues, gilets, casques...', 'order'=>5],
            ['name'=>'Batteries & Chargeurs',  'slug'=>'batteries',    'icon'=>'🔋', 'description'=>'Batteries et chargeurs', 'order'=>6],
            ['name'=>'Optiques & Visées',      'slug'=>'optiques',     'icon'=>'🔭', 'description'=>'Red dot, lunettes, iron sights...', 'order'=>7],
            ['name'=>'Pièces & Upgrades',      'slug'=>'pieces',       'icon'=>'⚙️', 'description'=>'Pièces détachées et améliorations', 'order'=>8],
            ['name'=>'Munitions & Accessoires','slug'=>'accessoires',  'icon'=>'🎒', 'description'=>'Billes, speedloaders, accessoires...', 'order'=>9],
            ['name'=>'Divers',                 'slug'=>'divers',       'icon'=>'📦', 'description'=>'Autres équipements', 'order'=>10],
        ];
        foreach ($cats as $cat) {
            \DB::table('listing_categories')->insert(array_merge($cat, ['created_at'=>now(),'updated_at'=>now()]));
        }
    }
    public function down(): void { Schema::dropIfExists('listing_categories'); }
};
