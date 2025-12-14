<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $data = Cache::remember('analytics:home:v1', 300, function () {
            $top6Products = Product::query()
                ->where('status', 'available')
                ->orderByDesc('rating_avg')
                ->orderByDesc('rating_count')
                ->limit(6)
                ->get(['id', 'name', 'price', 'category', 'type', 'image1', 'image2', 'image3', 'rating_avg', 'rating_count']);

            $top8Categories = Product::query()
                ->where('status', 'available')
                ->selectRaw('category, COUNT(*) as total')
                ->groupBy('category')
                ->orderByDesc('total')
                ->limit(8)
                ->get();

            return [
                'message' => 'Analytics data',
                'top_products' => $top6Products,
                'top_categories' => $top8Categories,
            ];
        });

        return response()->json($data, 200);
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
