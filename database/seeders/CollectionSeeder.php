<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $collections = [
            [
                'name' => 'Collection Hiver 2025',
                'image' => 'http://127.0.0.1:8000/images/collection.jpg',
                'description' => 'Découvrez les tendances incontournables de l\'hiver 2025, avec des pièces chaudes et stylées.',
                'start_date' => '2025-01-01',
                'end_date' => '2025-03-31',
            ],
            [
                'name' => 'Collection Printemps 2025',
                'image' => 'http://127.0.0.1:8000/images/th10.jpg',
                'description' => 'La collection Printemps 2025 allie légèreté et fraîcheur avec des couleurs vives et des tissus naturels.',
                'start_date' => '2025-04-01',
                'end_date' => '2025-06-30',
            ],
            [
                'name' => 'Collection Été 2025',
                'image' => 'http://127.0.0.1:8000/images/th5.jpg',
                'description' => 'L\'été 2025 se prépare avec des vêtements légers, confortables et adaptés à la chaleur estivale.',
                'start_date' => '2025-07-01',
                'end_date' => '2025-09-30',
            ],
            [
                'name' => 'Collection Automne 2025',
                'image' => 'http://127.0.0.1:8000/images/th2.jpg',
                'description' => 'Explorez des tenues élégantes pour l\'automne 2025, avec des couleurs chaudes et des textures douces.',
                'start_date' => '2025-10-01',
                'end_date' => '2025-12-31',
            ],
            [
                'name' => 'Collection intemporelle',
                'image' => null, // Pas d'image
                'description' => 'Une collection intemporelle qui reste pertinente toute l\'année.',
                'start_date' => '2024-01-01',
                'end_date' => null, // Pas de date de fin
            ],
        ];

        foreach ($collections as $collection) {
            Collection::create($collection);
        }
    }
}
