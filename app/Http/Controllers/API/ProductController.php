<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Repositories\Abstract\ProductRepositoryInterface;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    protected $products;

    public function __construct(ProductRepositoryInterface $products)
    {
        $this->products = $products;
    }

    // Menampilkan semua produk
    public function index()
    {
        return ProductResource::collection($this->products->all());
    }

    // Menampilkan produk tertentu
    public function show($id)
    {
        return new ProductResource($this->products->find($id));
    }

    // Menambahkan produk baru
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:goods,service',
            'description' => 'required|string',
            'quantity' => 'nullable|integer|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'in:available,unavailable',
        ]);

        $validated['user_id'] = auth()->id();

        $product = $this->products->create($validated);

        return new ProductResource($product);
    }

    // Mengupdate produk
    public function update(Request $request, $id)
    {
        $product = $this->products->find($id);

        Gate::authorize('update', $product);

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:goods,service',
            'description' => 'sometimes|required|string',
            'quantity' => 'sometimes|nullable|integer|min:0',
            'price' => 'sometimes|required|numeric|min:0',
            'status' => 'in:available,unavailable',
        ]);

        $updated = $this->products->update($id, $validated);

        return new ProductResource($updated);
    }

    // Menghapus produk
    public function destroy($id)
    {
        $product = $this->products->find($id);

        Gate::authorize('delete', $product);

        $this->products->delete($id);

        return response()->json(['message' => 'Product deleted']);
    }
}

