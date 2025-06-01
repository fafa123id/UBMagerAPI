<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nego;
use App\Models\Product;
use Illuminate\Http\Request;

class NegoController extends Controller
{
    /**
     * POST: /api/nego
     * (Request a negotiation for a product.)
     * This method allows a user to request a negotiation on a product by providing the product ID and the proposed negotiation price.
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
     * GET: /api/nego
     * (Get all negotiation requests made by the authenticated user.)
     * This method retrieves all negotiation requests made by the authenticated user, filtered by status if provided.
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
     * GET: /api/nego/{id}
     * (Get the details of a specific negotiation request made by the authenticated user.)
     * This method retrieves the details of a specific negotiation request made by the authenticated user, including the associated product.
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
     * GET: /api/nego/cancel/{id}
     * (Cancel a negotiation request made by the authenticated user.)
     * This method allows the authenticated user to cancel a negotiation request they have made.
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
     * GET: /api/nego-seller
     * (Get all negotiation requests for products owned by seller.)
     * This method retrieves all negotiation requests for products owned by the authenticated user, filtered by status if provided.
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
     * GET: /api/nego-seller/{id}
     * (Get the details of a specific negotiation request for products owned by the seller.)
     * This method retrieves the details of a specific negotiation request for products owned by the authenticated user, including the associated product.
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
     * GET: /api/nego/decline/{id}
     * (Decline a negotiation request for products owned by the seller.)
     * This method allows the authenticated user to decline a negotiation request they have received for a product they own.
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
     * GET: /api/nego/accept/{id}
     * (Accept a negotiation request for products owned by the seller.)
     * This method allows the authenticated user to accept a negotiation request they have received for a product they own.
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
