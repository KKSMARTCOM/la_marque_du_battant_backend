<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function index(Request $request)
    {
        try {

            $query = Product::query();

            // Filtrer par nom
            if ($request->filled('name')) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            }

            // Filtrer par catégorie
            if ($request->filled('category') && $request->category !== "tous") {
                $category = Category::where('name', 'LIKE', '%' . $request->category . '%')->first();

                $query->where('category_id', $category->id);
            }

            // Filtrer par taille
            if ($request->filled('size') && $request->size !== null && $request->size !== "tous") {
                $query->where('size', 'LIKE', '%' . $request->size . '%');
            }

            // Filtrer par couleur
            if ($request->filled('color')) {
                $query->where('color', 'LIKE', '%' . $request->color . '%');
            }

            // Filtrer par statut
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filtrer par catégorie
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Filtrer par collection
            if ($request->filled('collection_id')) {
                $query->where('collection_id', $request->collection_id);
            }

            // Tri par prix (croissant ou décroissant)
            if ($request->filled('sort_price')) {
                $sortOrder = $request->sort_price === 'asc' ? 'asc' : 'desc';
                $query->orderBy('price', $sortOrder);
            }

            $products = $query->with('categorie')->get();

            return response()->json([
                "success" => true,
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function getFeaturesProducts($id)
    {
        try {
            $product = Product::find($id);

            $featuresProduct = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)->limit(4)->get();

            return response()->json([
                "success" => true,
                'data' => $featuresProduct
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAccessories()
    {
        try {
            $category = Category::where('name', 'Accessoires')->first();

            $accessories = Product::where('category_id', $category->id)->inRandomOrder()->take(4)->get();

            return response()->json([
                "success" => true,
                'data' => $accessories
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            //code...
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    "success" => false,
                    'message' => 'Product non disponible'
                ], 404);
            }

            return response()->json([
                "success" => true,
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(ProductRequest $request)
    {
        try {
            //code...
            $images = [];

            if ($request->hasFile('imageUrl')) {
                foreach ($request->file('imageUrl') as $image) {
                    $path = $image->store('pictures');
                    $images[] = $path;
                }
            }

            //dd($images);

            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'size' => $request->size,
                'color' => $request->color,
                'quantity' => $request->quantity,
                'imageUrl' => $images,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
            ]);

            return response()->json(['data' => $product, 'message' => 'Product ajouté avec succès.'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            //code...
            //dd($request->all());
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['message' => 'Product non disponible'], 404);
            }

            $images = $product->images ?? [];

            if ($request->hasFile('imageUrl')) {
                foreach ($request->file('imageUrl') as $image) {
                    $path = $image->store('pictures');
                    if (!in_array($path, $images)) {
                        $images[] = $path;
                    }
                }
            }

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'size' => $request->size,
                'color' => $request->color,
                'quantity' => $request->quantity,
                'imageUrl' => $images,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
            ]);
            return response()->json(['data' => $product, 'message' => 'Product mis à jour avec succès.'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            //code...
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['message' => 'Produit non disponoble.'], 404);
            }

            $product->delete(); // Supprime le produit

            return response()->json(['message' => 'Product supprimé avec succès.'], 204);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur au niveau du serveur', 'error' => $e->getMessage()], 500);
        }
    }
}
