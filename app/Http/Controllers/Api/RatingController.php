<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rating;

class RatingController extends Controller
{
    /**
     * POST: /api/rating/{id}
     * 
     * Store a new rating for a product by buyer.
     * This method allows a user to rate a product after purchasing it, including an optional comment and image.
     * @authenticated
     */
    public function store(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = auth()->user()->order()->where('id', $id)->first();
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or does not belong to the authenticated user.',
            ], 404);
        }
        if ($order->status !== 'finished') {
            return response()->json([
                'success' => false,
                'message' => 'You can only rate products from finished orders.',
            ], 400);
        }
        if ($order->isRated()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already rated this product.',
            ], 400);
        }
        $validatedData['product_id'] = $order->product_id;
        if ($request->hasFile('image')) {
            // Store the image and get its path
            $imagePath = config('filesystems.disks.s3.url') . $request->file('image')->store('ratings', 'public');
            $validatedData['image'] = $imagePath;
        }


        // Create a new rating for the product
        $order->update(['is_rated' => true]);
        $rating = auth()->user()->ratings()->create($validatedData);

        return response()->json([
            'success' => true,
            'data' => $rating,
        ], 201);
    }
    private function buildQuery($rating, $id)
    {

        $ratings = Rating::with(['user:id,name,image'])->where('product_id', $id);

        if ($rating !== "all") {
            $ratings->where('rating', '=', $rating);
        }


        return $ratings->orderBy('created_at', 'desc')->orderBy('rating', 'desc');
    }
    /**
     * GET: /api/rating/count/{id}
     * 
     * Count ratings for a specific product with optional filtering by rating value.
     * This method allows users to get the count of ratings for a product, filtered by a maximum rating value.
     * @authenticated
     */
    public function count(Request $request, $id)
    {
        $ratings = $this->buildQuery(5, $id);
        return response()->json([
            'success' => true,
            'data' => [
                'count' => $ratings->count(),
            ],
        ], 200);
    }
    /**
     * GET: /api/rating/page-count/{id}
     * 
     * Retrieve the page count of ratings for a specific product with optional filtering by rating value.
     * This method allows users to get the total number of pages of ratings for a product, filtered by a maximum rating value, based on a specified number of ratings per page.
     * @authenticated
     */
    public function pageCount(Request $request, $id)
    {
        $rating = $request->query('rating', "all");
        $perpage = $request->query('perpage', 5);
        $ratings = $this->buildQuery($rating, $id);
        $pageCount = ceil($ratings->count() / $perpage);
        return response()->json([
            'success' => true,
            'data' => [
                'page_count' => $pageCount,
            ],
        ], 200);
    }
    /**
     * GET: /api/rating/{id}
     * 
     * Retrieve ratings for a specific product with optional filtering by rating value.
     * This method allows users to fetch ratings for a product, filtered by a maximum rating value, and supports pagination.
     * @authenticated
     */
    public function get(Request $request, $id)
    {
        $rating = $request->query('rating', "all");
        $perpage = $request->query('perpage', 5);
        $page = $request->query('page', 1);
        $ratings = $this->buildQuery($rating, $id)->skip(($page - 1) * $perpage)->take($perpage)->get();

        return response()->json([
            'success' => true,
            'data' => $ratings,
        ], 200);
    }
}
