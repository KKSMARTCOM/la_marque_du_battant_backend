<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            [
                'name' => 'Homme',
                'description' => 'Toutes les catégories de vêtements et accessoires pour hommes, allant des tenues décontractées aux vêtements formels.',
            ],
            [
                'name' => 'Femme',
                'description' => 'Les collections de mode pour femmes, allant des robes élégantes aux vêtements plus décontractés, pour chaque occasion.',
            ],
            [
                'name' => 'Accessoires',
                'description' => 'Une variété d\'accessoires de mode, incluant tableaux de citations, mugs personnalisé et casquettes.',
            ],
            [
                'name' => 'Tableaux',
                'description' => 'Ajoutez une touche artistique à votre intérieur avec nos magnifiques tableaux.',
            ],
            [
                'name' => 'Mugs',
                'description' => 'Des mugs uniques pour accompagner vos moments café ou thé.',
            ],
            [
                'name' => 'Casquettes',
                'description' => 'Protégez-vous du soleil avec style grâce à nos casquettes personnalisées.',
            ]
        ];

        foreach ($categories as $categorie) {
            Category::create($categorie);
        }
    }
}
