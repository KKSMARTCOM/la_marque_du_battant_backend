<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = Category::all();
        $collection = Collection::first();

        $products = [
            // Homme
            [
                'name' => 'T-shirt Homme Classique',
                'description' => 'Un t-shirt simple et élégant, parfait pour un look décontracté.',
                'price' => 1500.99,
                'size' => json_encode(['L', 'XL']),
                'color' => 'Noir',
                'quantity' => 100,
                'main_image' => 'http://127.0.0.1:8000/images/th1.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/th2.jpg',
                    'http://127.0.0.1:8000/images/th11.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Homme')->first()->id,
                'collection_id' =>  null,
            ],
            [
                'name' => 'Casquette Homme',
                'description' => 'Casquette élégante pour un style décontracté.',
                'price' => 2200.50,
                'size' => json_encode(['unique']),
                'color' => 'Noir',
                'quantity' => 50,
                'main_image' => 'http://127.0.0.1:8000/images/ca1.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/ca2.jpg',
                    'http://127.0.0.1:8000/images/ca3.jpg',
                    'http://127.0.0.1:8000/images/ca4.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Homme')->first()->id,
                'collection_id' => $collection ? $collection->id : null,
            ],
            [
                'name' => 'Pantalon Homme Slim',
                'description' => 'Un pantalon slim, confortable et stylé, idéal pour les sorties.',
                'price' => 2900.99,
                'size' => json_encode(['M', 'L']),
                'color' => 'Noir',
                'quantity' => 70,
                'main_image' => 'http://127.0.0.1:8000/images/p1.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/p2.jpg',
                    'http://127.0.0.1:8000/images/p3.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Homme')->first()->id,
                'collection_id' =>  null,
            ],
            [
                'name' => 'Chemise Homme',
                'description' => 'Chemise élégante à carreaux, idéale pour les occasions décontractées.',
                'price' => 1900.99,
                'size' => json_encode(['L', 'XL']),
                'color' => 'Belge',
                'quantity' => 90,
                'main_image' => 'http://127.0.0.1:8000/images/th6.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/th7.jpg',
                    'http://127.0.0.1:8000/images/th8.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Homme')->first()->id,
                'collection_id' =>  null,
            ],

            // Femme
            [
                'name' => 'T-shirt Femme Col V',
                'description' => 'T-shirt à col V, léger et confortable, idéal pour l\'été.',
                'price' => 1800.50,
                'size' => json_encode(['S', 'M']),
                'color' => 'Blanc',
                'quantity' => 80,
                'main_image' => 'http://127.0.0.1:8000/images/th3.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/th4.jpg',
                    'http://127.0.0.1:8000/images/th5.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Femme')->first()->id,
                'collection_id' =>  null,
            ],
            [
                'name' => 'Robe Femme Été',
                'description' => 'Robe légère et élégante pour les journées chaudes.',
                'price' => 2500.99,
                'size' => json_encode(['S', 'M', 'L']),
                'color' => 'Blanc',
                'quantity' => 60,
                'main_image' => 'http://127.0.0.1:8000/images/th9.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/th10.jpg',
                    'http://127.0.0.1:8000/images/th11.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Femme')->first()->id,
                'collection_id' => $collection ? $collection->id : null,
            ],
            [
                'name' => 'Jeans Femme Taille Haute',
                'description' => 'Jeans slim taille haute, parfaits pour un look élégant.',
                'price' => 3400.99,
                'size' => json_encode(['M', 'L']),
                'color' => 'Belge',
                'quantity' => 40,
                'main_image' => 'http://127.0.0.1:8000/images/cu1.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/cu2.jpg',
                    'http://127.0.0.1:8000/images/cu3.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Femme')->first()->id,
                'collection_id' => null,
            ],
            [
                'name' => 'Culotte Femme en Coton',
                'description' => 'Culotte en coton, confortable pour un usage quotidien.',
                'price' => 9000.99,
                'size' => json_encode(['M', 'L']),
                'color' => 'Belge',
                'quantity' => 120,
                'main_image' => 'http://127.0.0.1:8000/images/po1.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/po2.jpg',
                    'http://127.0.0.1:8000/images/po3.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Femme')->first()->id,
                'collection_id' => $collection ? $collection->id : null,
            ],

            // Accessoires
            [
                'name' => 'Tableau Citation Motivation',
                'description' => 'Un tableau avec une citation inspirante pour décorer votre intérieur.',
                'price' => 2900.99,
                'size' => json_encode(['unique']),
                'color' => 'Blanc',
                'quantity' => 30,
                'main_image' => 'http://127.0.0.1:8000/images/ta1.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/ta2.jpg',
                    'http://127.0.0.1:8000/images/ta3.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Accessoires')->first()->id,
                'collection_id' => null,
            ],
            [
                'name' => 'Tableau Citation Sérénité',
                'description' => 'Un tableau avec une citation inspirante pour décorer votre intérieur.',
                'price' => 2900.99,
                'size' => json_encode(['unique']),
                'color' => 'Blanc',
                'quantity' => 30,
                'main_image' => 'http://127.0.0.1:8000/images/ta4.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/ta5.jpg',
                    'http://127.0.0.1:8000/images/ta6.jpg',
                    'http://127.0.0.1:8000/images/ta6.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Accessoires')->first()->id,
                'collection_id' => null,
            ],
            [
                'name' => 'Mug Personnalisé',
                'description' => 'Mug personnalisable avec votre message ou photo.',
                'price' => 1200.99,
                'size' => json_encode(['unique']),
                'color' => 'Belge',
                'quantity' => 200,
                'main_image' => 'http://127.0.0.1:8000/images/t1.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/t2.jpg',
                    'http://127.0.0.1:8000/images/t3.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Accessoires')->first()->id,
                'collection_id' => $collection ? $collection->id : null,
            ],
            [
                'name' => 'Tasse magique Personnalisé',
                'description' => 'Mug personnalisable avec votre message ou photo.',
                'price' => 1200.99,
                'size' => json_encode(['unique']),
                'color' => 'Belge',
                'quantity' => 200,
                'main_image' => 'http://127.0.0.1:8000/images/t4.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/t5.jpg',
                    'http://127.0.0.1:8000/images/t1.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Accessoires')->first()->id,
                'collection_id' => $collection ? $collection->id : null,
            ],
            [
                'name' => 'Bob du battant',
                'description' => 'Ceinture en cuir de qualité supérieure, idéale pour compléter votre look.',
                'price' => 1900.50,
                'size' => json_encode(['unique']),
                'color' => 'Noir',
                'quantity' => 50,
                'main_image' => 'http://127.0.0.1:8000/images/bo1.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/bo2.jpg',
                    'http://127.0.0.1:8000/images/bo3.jpg',
                    'http://127.0.0.1:8000/images/bo4.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Accessoires')->first()->id,
                'collection_id' => $collection ? $collection->id : null,
            ],
            [
                'name' => 'Autocollant avec citation',
                'description' => 'Sac à main élégant pour femme, parfait pour vos sorties.',
                'price' => 4500.00,
                'size' => json_encode(['unique']),
                'color' => 'Belge',
                'quantity' => 30,
                'main_image' => 'http://127.0.0.1:8000/images/a2.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/a3.jpg',
                    'http://127.0.0.1:8000/images/a4.jpg',
                    'http://127.0.0.1:8000/images/a5.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Accessoires')->first()->id,
                'collection_id' => null,
            ],
            [
                'name' => 'Autocollant du battant',
                'description' => 'Sac à main élégant pour femme, parfait pour vos sorties.',
                'price' => 4500.00,
                'size' => json_encode(['unique']),
                'color' => 'Belge',
                'quantity' => 30,
                'main_image' => 'http://127.0.0.1:8000/images/a6.jpg',
                'additional_images' => json_encode([
                    'http://127.0.0.1:8000/images/a7.jpg',
                    'http://127.0.0.1:8000/images/a8.jpg'
                ]),
                'status' => '1',
                'category_id' => $categories->where('name', 'Accessoires')->first()->id,
                'collection_id' => null,
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
