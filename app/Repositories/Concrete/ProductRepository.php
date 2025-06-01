<?php
namespace App\Repositories\Concrete;

use App\Http\Resources\failReturn;
use App\Http\Resources\ProductResource;
use App\Http\Resources\successReturn;
use App\Models\Product;
use App\Repositories\Abstract\ProductRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ProductRepository implements ProductRepositoryInterface
{
    public function all($type, $category , $query)
    {
        $products = Product::query();
        if ($type) {
            $products->whereIn('type', $type);
        }
        if ($category) {
            $products->whereIn('category', $category);
        }
        if ($query) {
            $products->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhereHas('user', function ($userQuery) use ($query) {
                      $userQuery->where('name', 'like', '%' . $query . '%');
                  });
            });
        }
        
        return $products->get();
    }
    public function getType()
    {
        return Product::distinct('type')->pluck('type');
    }
    public function getCategoryByType($type)
    {
        return Product::where('type', $type)->distinct('category')->pluck('category');
    }
    public function find($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return new failReturn([
                'message' => 'Product not found',
                'status' => 404,
            ]);
        }
        return new successReturn(
            [
                'status' => 200,
                'message' => 'Product retrieved successfully',
                'data' => new ProductResource($product),
            ]
        );
    }

    public function create(array $data)
    {
        $product = auth()->user()->product()->create($data);
        return new successReturn(
            [
                'status' => 201,
                'message' => 'Product created successfully',
                'data' => new ProductResource($product),
            ]
        );
    }

    public function update($id, array $data)
    {
        $product = $this->tryCatch($id);
        if (!$product) {
            return new failReturn([
                'message' => 'Product not found',
                'status' => 404,
            ]);
        }
        $product->update($data);
        return new successReturn(
            [
                'status' => 200,
                'message' => 'Product updated successfully',
                'data' => new ProductResource($product),
            ]
        );
    }

    public function delete($id)
    {
        $product = $this->tryCatch($id);
        if (!$product) {
            return new failReturn([
                'message' => 'Product not found',
                'status' => 404,
            ]);
        }
        Storage::disk('s3')->delete($product->image1);
        
        if ($product->image2!== null) {
            Storage::disk('s3')->delete($product->image2);
        }
        if ($product->image3!== null) {
            Storage::disk('s3')->delete($product->image3);
        }
        $product->delete();
        return new successReturn(
            [
                'status' => 200,
                'message' => 'Product deleted successfully'
            ]
        );
    }
    private function tryCatch($id)
    {
        try {
            $product = auth()->user()->product()->findOrFail($id);
        } catch (\Exception $e) {
            return null; 
        }
        return $product;
    }
}

