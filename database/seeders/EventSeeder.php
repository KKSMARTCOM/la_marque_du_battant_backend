<?php

namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $events = [
            [
                'name' => 'Festival de musique d’Abidjan',
                'description' => 'Un grand festival de musique avec des artistes internationaux et locaux.',
                'image' => 'http://127.0.0.1:8000/images/event1.jpeg',
                'price' => 25.00,
                'country' => 'Côte d\'Ivoire',
                'address' => 'Palais des Congrès, Abidjan, Côte d\'Ivoire',
                'startDate' => Carbon::create(2025, 5, 20, 18, 0, 0),
                'endDate' => Carbon::create(2025, 5, 22, 23, 59, 59),
            ],
            [
                'name' => 'Marché de Noël à Dakar',
                'description' => 'Le marché de Noël annuel de Dakar avec des artisans locaux et des produits traditionnels.',
                'image' => 'http://127.0.0.1:8000/images/event2.jpeg',
                'price' => 5.00,
                'country' => 'Sénégal',
                'address' => 'Place de l’Indépendance, Dakar, Sénégal',
                'startDate' => Carbon::create(2025, 12, 1, 10, 0, 0),
                'endDate' => Carbon::create(2025, 12, 24, 20, 0, 0),
            ],
            [
                'name' => 'Festival du Film de Lomé',
                'description' => 'Festival international du film à Lomé, mettant en avant des productions africaines.',
                'image' => 'http://127.0.0.1:8000/images/event3.jpeg',
                'price' => 15.00,
                'country' => 'Togo',
                'address' => 'Centre Culturel Français, Lomé, Togo',
                'startDate' => Carbon::create(2025, 10, 5, 9, 0, 0),
                'endDate' => Carbon::create(2025, 10, 10, 23, 59, 59),
            ],
            [
                'name' => 'Salon de l’Entrepreneuriat de Ouagadougou',
                'description' => 'Un salon dédié aux jeunes entrepreneurs et aux opportunités d’affaires.',
                'image' => 'http://127.0.0.1:8000/images/event4.jpeg',
                'price' => 20.00,
                'country' => 'Burkina Faso',
                'address' => 'Hotel Azalai, Ouagadougou, Burkina Faso',
                'startDate' => Carbon::create(2025, 11, 12, 8, 0, 0),
                'endDate' => Carbon::create(2025, 11, 14, 18, 0, 0),
            ],
            [
                'name' => 'Carnaval de Banjul',
                'description' => 'Le plus grand carnaval de la Gambie, une célébration de la culture locale.',
                'image' => 'http://127.0.0.1:8000/images/event5.jpeg',
                'price' => 10.00,
                'country' => 'Gambie',
                'address' => 'Plage de Banjul, Banjul, Gambie',
                'startDate' => Carbon::create(2025, 2, 14, 14, 0, 0),
                'endDate' => Carbon::create(2025, 2, 16, 23, 59, 59),
            ],
            [
                'name' => 'Festival de Danse de Conakry',
                'description' => 'Un festival annuel de danse qui réunit des danseurs du monde entier.',
                'image' => 'http://127.0.0.1:8000/images/event6.jpeg',
                'price' => 12.00,
                'country' => 'Guinée',
                'address' => 'Centre Culturel Franco-Guinéen, Conakry, Guinée',
                'startDate' => Carbon::create(2025, 8, 10, 17, 0, 0),
                'endDate' => Carbon::create(2025, 8, 12, 21, 0, 0),
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
