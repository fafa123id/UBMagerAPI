<?php
namespace App\Repositories\Concrete;

use App\Events\CheckAmount;
use App\Events\CheckQuantity;
use App\Http\Resources\CartResource;
use App\Http\Resources\failReturn;
use App\Http\Resources\successReturn;
use App\Models\Product;
use App\Repositories\Abstract\CartRepositoryInterface;


class CartRepository implements CartRepositoryInterface
{
    public function all()
    {
        return auth()->user()->cart;
    }

    public function find($id)
    {
        $cart = $this->tryCatch($id);
        if (!$cart) {
            return new failReturn([
                'message' => 'Cart not found',
                'status' => 404,
            ]);
        }
        return new successReturn(
            [
                'status' => 200,
                'message' => 'Cart retrieved successfully',
                'data' => new CartResource($cart),
            ]
        );
    }

    public function create(array $data)
    {
        $product = $product = Product::findOrFail($data['product_id']);
        if (!$product) {
            return new failReturn([
                'message' => 'Product not found',
                'status' => 404,
            ]);
        }
        $quantity = event(new CheckQuantity($product, $data['amount']));
        $amount = event(new CheckAmount($product, $data['amount']))[0];
        if (!$quantity || !$amount) {
            return new failReturn([
                'message' => 'Product is out of stock',
                'status' => 422,
            ]);
        }
        $datas = [
            'price' => $product->price,
            'product_id' => $product->id,
            'amount' => $amount,
        ];
        return new successReturn([
            'message' => 'Cart created',
            'data' => new CartResource(auth()->user()->cart()->create($datas)),
            'status' => 201,
        ]);
    }

    public function update($id, array $data)
    {
        $cart = $this->tryCatch($id);
        if (!$cart) {
            return new failReturn([
                'message' => 'Cart not found',
                'status' => 404,
            ]);
        }
        $product = Product::findOrFail($cart->product_id);
        if (!$product) {
            return new failReturn([
                'message' => 'Product not found',
                'status' => 404,
            ]);
        }
        $quantity = event(new CheckQuantity($product, $data['amount']))[0];
        $amount = event(new CheckAmount($product, $data['amount']))[0];
        if (!$amount || !$quantity) {
            return new failReturn([
                'message' => 'Product is out of stock',
                'status' => 422,
            ]);
        }
        $datas = ['amount' => $amount];
        $cart->update($datas);
        return new successReturn([
            'message' => 'Cart updated',
            'data' => new CartResource($cart),
            'status' => 200,
        ]);
    }

    public function delete($id)
    {
        $cart = $this->tryCatch($id);
        if (!$cart) {
            return null;
        }
        return $cart->delete();
    }
    public function deleteAll()
    {
        return auth()->user()->cart()->delete();
    }

    private function tryCatch($cartId)
    {
        try {
            $cart = auth()->user()->cart()->findOrFail($cartId);
        } catch (\Exception $e) {
            return null; 
        }
        return $cart;
    }
}