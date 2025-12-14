<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $top6Products = Product::query()
            ->where('status', 'available')
            ->withAvg('ratings', 'rating')      // bikin kolom: ratings_avg_rating
            ->withCount('ratings')              // opsional: untuk filter jumlah rating
            ->having('ratings_count', '>=', 5)  // opsional biar gak produk 1 rating langsung nangkring
            ->orderByDesc('ratings_avg_rating')
            ->orderByDesc('ratings_count')      // tie-breaker (opsional)
            ->limit(6)
            ->get();
        $top8Categories = Product::query()
            ->where('status', 'available')
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(8)
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
