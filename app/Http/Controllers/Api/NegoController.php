<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nego;
use App\Models\Product;
use Illuminate\Http\Request;

class NegoController extends Controller
{
    /**
     * @authenticated
     */
    public function requestNego(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'nego_price' => 'required|numeric|min:0',
        ]);
        $product = Product::findOrFail($request->product_id);
        if (!$product->isNegotiable()) {
            return response()->json([
                'success' => false,
                'message' => 'This product is not negotiable.',
            ], 400);
        }
        // if ($product->user_id == auth()->id()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You cannot negotiate your own product.',
        //     ], 403);
        // }


        $nego = auth()->user()->nego()->create([
            'product_id' => $product->id,
            'nego_price' => $request->nego_price,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'data' => $nego,
            'message' => 'Nego request created successfully.',
        ]);
    }
    /**
     * @authenticated
     */
    public function myNegos()
    {
        $filter = request()->query('filter');
        if ($filter) {
            $filter = explode(',', $filter);
            $filter = array_map('trim', $filter);
        }
        $negos = auth()->user()->nego()->with('product')->whereIn('status', $filter ?: ['pending', 'accepted', 'rejected', 'cancelled'])->get();

        return response()->json([
            'success' => true,
            'data' => $negos,
        ]);
    }
    /**
     * @authenticated
     */
    public function negoDetail($id)
    {
        $nego = auth()->user()->nego()->with('product')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $nego,
        ]);
    }
    /**
     * @authenticated
     */
    public function cancelNego($id)
    {
        $nego = auth()->user()->nego()->findOrFail($id);
        $nego->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Nego request cancelled successfully.',
        ]);
    }

    //for seller
    /**
     * @authenticated
     */
    public function sellerAll()
    {
        $filter = request()->query('filter');
        if ($filter) {
            $filter = explode(',', $filter);
            $filter = array_map('trim', $filter);
        }
        $negos = Nego::with('product', 'user')
            ->whereHas('product', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->whereIn('status', $filter ?: ['pending', 'accepted', 'rejected', 'cancelled'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $negos,
        ]);
    }
    /**
     * @authenticated
     */
    public function show($id)
    {
        $nego = Nego::with('product')
            ->whereHas('product', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $nego,
        ]);
    }
    /**
     * @authenticated
     */
    public function declineNego($id)
    {
        $nego = Nego::with('product')
            ->whereHas('product', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->findOrFail($id);
        if ($nego->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Nego request is not in pending status.',
            ], 400);
        }
        $nego->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'Nego request declined successfully.',
        ]);
    }
    /**
     * @authenticated
     */
    public function acceptNego($id)
    {
        $nego = Nego::with('product')
            ->whereHas('product', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->findOrFail($id);
        if ($nego->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Nego request is not in pending status.',
            ], 400);
        }
        $nego->update(['status' => 'accepted']);

        return response()->json([
            'success' => true,
            'message' => 'Nego request accepted successfully.',
        ]);
    }
}
