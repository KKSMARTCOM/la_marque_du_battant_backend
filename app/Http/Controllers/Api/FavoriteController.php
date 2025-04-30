<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    // Ajouter ou supprimer un produit des favoris
    public function toggleFavorite(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'size_selected' => 'required',
        ], [
            'size_selected.required' => 'La taille de l\'article est requis.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();

        $product = Product::findOrFail($productId);


        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "Vous devez vous connecter avant d'ajouter aux favoris."
            ], 401);
        }

        if (!$product) {
            return response()->json([
                "success" => false,
                "message" => "Produit non disponible."
            ], 404);
        }

        // Vérifier si le favori existe
        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            // Supprimer des favoris
            $favorite->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produit retiré des favoris.',
            ], 200);
        } else {
            // Ajouter aux favoris
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'size_selected' => $request->size_selected,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté aux favoris.',
            ], 200);
        }
    }

    // Récupérer les favoris d'un utilisateur
    public function getFavorites(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "Utilisateur non connecté."
            ], 401);
        }

        // Récupérer les produits favoris de l'utilisateur
        $favorites = Favorite::where('user_id', $user->id)
            ->with('product')
            ->get();

        return response()->json([
            "success" => true,
            'data' => $favorites
        ]);
    }
}
