<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ForumCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'administrateur
        User::firstOrCreate(
		['email' => 'admin@airsoftpaca.fr'],
		[
			'name'     => 'Administrateur',
			'password' => Hash::make('ChangeThisPassword123!'),
			'role'     => 'admin',
		]
	);

        // Créer les catégories du forum
        $categories = [
            ['name' => 'Annonces',          'slug' => 'annonces',           'icon' => 'megaphone',     'order' => 1, 'description' => 'Actualités et annonces importantes'],
            ['name' => 'Discussions générales', 'slug' => 'general',        'icon' => 'chat',           'order' => 2, 'description' => 'Tous sujets autour de l\'airsoft'],
            ['name' => 'Terrain & Parties', 'slug' => 'terrains-parties',   'icon' => 'map',            'order' => 3, 'description' => 'Spots, fields, retours de parties'],
            ['name' => 'Matériel',          'slug' => 'materiel',           'icon' => 'wrench',         'order' => 4, 'description' => 'Répliques, équipements, conseils'],
            ['name' => 'Marché',            'slug' => 'marche',             'icon' => 'shopping-cart',  'order' => 5, 'description' => 'Achats et ventes entre airsofteurs'],
            ['name' => 'Recrutements',      'slug' => 'recrutements',       'icon' => 'user-plus',      'order' => 6, 'description' => 'Escouades en recherche de membres'],
        ];

        foreach ($categories as $cat) {
		ForumCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
	}
    }
}
