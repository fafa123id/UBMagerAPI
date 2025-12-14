<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $data = Cache::remember('analytics:home:v1', 300, function () {
            $top6Products = Product::query()
                ->joinSub(
                    Rating::query()
                        ->selectRaw('product_id, AVG(rating) as rating_avg, COUNT(*) as rating_count')
                        ->groupBy('product_id'),
                    'r',
                    fn($join) => $join->on('products.id', '=', 'r.product_id')
                )
                ->where('products.status', 'available')
                ->orderByDesc('r.rating_count') // biar yang ratingnya banyak naik
                ->limit(6)
                ->get([
                    'products.id',
                    'products.name',
                    'products.price',
                    'products.category',
                    'products.type',
                    'products.image1',
                    'products.image2',
                    'products.image3',
                    'r.rating_avg',
                    'r.rating_count',
                ]);

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
