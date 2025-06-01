<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RatingController extends Controller
{
    /**
     * POST: /api/rating/{id}
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
}
