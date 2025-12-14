<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $top6Products = Product::with('ratings')
            ->where('status', 'available')
            ->get()
            ->sortByDesc(function ($product) {
                return $product->ratings->avg('rating');
            })
            ->take(6);
        $top8Categories = Product::select('category')
            ->where('status', 'available')
            ->groupBy('category')
            ->orderByRaw('COUNT(*) DESC')
            ->take(8)
            ->pluck('category');

        return response()->json(
            [
                'message' => 'Analytics data',
                'top_products' => $top6Products,
                'top_categories' => $top8Categories
            ],
            200
        );
    }
    public function indexProfile()
    {
        $user = auth()->user();
        $transactionCount = $user->transaction()->count();
        $favoriteCount = $user->favorites()->count();
        $ratingCount = $user->ratings()->count();

        return response()->json(
            [
                'message' => 'User analytics data',
                'transaction_count' => $transactionCount,
                'favorite_count' => $favoriteCount,
                'rating_count' => $ratingCount
            ],
            200
        );
    }
}
