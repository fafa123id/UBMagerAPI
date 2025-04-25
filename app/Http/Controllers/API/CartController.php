<?php

namespace App\Http\Controllers\Api;

use App\Events\CheckAmount;
use App\Events\CheckQuantity;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\failReturn;
use App\Http\Resources\ProductResource;
use App\Http\Resources\successReturn;
use App\Models\Product;
use App\Repositories\Abstract\CartRepositoryInterface;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cart;
    public function __construct(CartRepositoryInterface $cart)
    {
        $this->cart = $cart;
    }
    public function index()
    {
        return new successReturn(
            [
                'status' => 200,
                'message' => 'Cart retrieved successfully',
                'data' => CartResource::collection($this->cart->all()),
            ]
        );
    }
    public function show($id)
    {
        return $this->cart->find($id);
    }
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
            'product_id' => 'required|integer|exists:products,id',
        ]);

        return $this->cart->create($request->all());
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
        ]);

        return $this->cart->update($id, [
            'amount' => $request->amount,
        ]);
    }
    public function addOne(Request $request, $id)
    {
        $cart = $this->cart->find($id);
        return $this->cart->update($id, [
            'amount' => $cart->amount + 1,
        ]);
    }
    public function removeOne(Request $request, $id)
    {
        $cart = $this->cart->find($id);
        if ($cart->amount == 1) {
            return $this->cart->delete($id);
        }
        return $this->cart->update($id, [
            'amount' => $cart->amount - 1,
        ]);
    }
    public function destroy($id)
    {
        $cart = $this->cart->delete($id);
        if (!$cart) {
            return new failReturn([
                'message' => 'Cart not found',
                'status' => 404
            ]);
        }
        return new successReturn([
            'message' => 'Cart deleted',
            'status' => 204
        ]);

    }
    public function destroyAll()
    {
        $this->cart->deleteAll();

        return new successReturn([
            'message' => 'All cart deleted',
            'status' => 204
        ]);
    }
}
