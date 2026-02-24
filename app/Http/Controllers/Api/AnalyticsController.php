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
       $data = Cache::get('analytics:home:v1');

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
