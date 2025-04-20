<?php
namespace App\Repositories\Concrete;

use App\Models\Product;
use App\Repositories\Abstract\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
        return Product::with('user')->get();
    }

    public function find($id)
    {
        return Product::with('user')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }
}

