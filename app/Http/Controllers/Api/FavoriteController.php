<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $productId = $request->input('product_id');
        // Check if the favorite already exists
        $existingFavorite = $user->favorites()->where('product_id', $productId)->first();
        if ($existingFavorite) {
            return response()->json([
                'success' => false,
                'message' => 'Product is already in favorites.',
            ], 400);
        }

        // Add to favorites
        $user->favorites()->create(['product_id' => $productId]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to favorites.',
        ], 201);
    }
    public function index()
    {
        $user = auth()->user();
        $favorites = $user->favorites()->with('product')->get();

        return response()->json([
            'success' => true,
            'data' => $favorites,
        ], 200);
    }
    public function destroy($productId)
    {
        $user = auth()->user();

        // Find the favorite entry
        $favorite = $user->favorites()->where('product_id', $productId)->first();
        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in favorites.',
            ], 404);
        }

        // Remove from favorites
        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from favorites.',
        ], 200);
    }
    public function countFavorites()
    {
        $user = auth()->user();
        $count = $user->favorites()->count();

        return response()->json([
            'success' => true,
            'data' => ['count' => $count],
        ], 200);
    }
}
