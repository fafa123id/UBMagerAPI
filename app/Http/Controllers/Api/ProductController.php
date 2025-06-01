<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\successReturn;
use App\Models\Product;
use App\Repositories\Abstract\ProductRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    protected $products;

    public function __construct(ProductRepositoryInterface $products)
    {
        $this->products = $products;
    }
    /**
     * Display a listing of the products.
     * This method retrieves all products based on the provided type, category, and query parameters.
     */
    public function index(Request $request)
    {
        $type = $request->query("type");
        $category = $request->query("category");
        $query = $request->query("query");

        $queries = $query ? $query : null;
        $typeList = $type && $type !== 'all' ? array_map('trim', explode(',', $type)) : null;
        $categoryList = $category && $category !== 'all' ? array_map('trim', explode(',', $category)) : null;
        return new successReturn(
            [
                'status' => 200,
                'message' => 'Product retrieved successfully',
                'data' => ProductResource::collection($this->products->all($typeList, $categoryList, $queries)),
            ]
        );
    }

    /**
     * Display a specific product by ID.
     * This method retrieves a product by its ID.
     */
    public function show($id)
    {
        return $this->products->find($id);
    }

    /**
     * Get all product's type.
     * This method retrieves all products of a specific type.
     */
    public function getType()
    {
        return $this->products->getType();
    }

    /**
     * Get all product's category by type.
     * This method retrieves all categories of products based on the provided type.
     */
    public function getCategoryByType($type)
    {
        return $this->products->getCategoryByType($type);
    }

    /**
     * Store a newly created product for seller.
     * This method allows the authenticated user to create a new product with the provided details.
     * @authenticated
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'category' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'nullable|integer|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'in:available,unavailable',
            'image1' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if (request()->hasFile('image1')) {
            $validated['image1'] = config('filesystems.disks.s3.url') . $request->file('image1')->store('images', 's3');
        }
        if (request()->hasFile('image2')) {
            $validated['image2'] = config('filesystems.disks.s3.url') . $request->file('image2')->store('images', 's3');
        }
        if (request()->hasFile('image3')) {
            $validated['image3'] = config('filesystems.disks.s3.url') . $request->file('image3')->store('images', 's3');
        }
        return $this->products->create($validated);
    }

    /**
     * Update the specified product by ID for seller.
     * This method allows the authenticated user to update an existing product with the provided details.
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'type' => 'sometimes|required|string',
            'category' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'quantity' => 'sometimes|nullable|integer|min:0',
            'price' => 'sometimes|required|numeric|min:0',
            'status' => 'in:available,unavailable',
            'image1' => 'sometimes|required|image|mimes:jpeg,png,jpg|max:2048',
            'image2' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image3' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $product = Product::findOrFail($id);
        if ($request->hasFile('image1')) {
            Storage::disk('s3')->delete($product->image1);
            $validated['image1'] = config('filesystems.disks.s3.url') . $request->file('image1')->store('images', 's3');
        }
        if ($request->hasFile('image2')) {
            // Delete the old image2 if it exists
            if ($product->image2 && Storage::disk('s3')->exists($product->image2)) {
                Storage::disk('s3')->delete($product->image2);
            }
            $validated['image2'] = config('filesystems.disks.s3.url') . $request->file('image2')->store('images', 's3');
        }
        if ($request->hasFile('image3')) {
            // Delete the old image3 if it exists
            if ($product->image3 && Storage::disk('s3')->exists($product->image3)) {
                Storage::disk('s3')->delete($product->image3);
            }
            $validated['image3'] = config('filesystems.disks.s3.url') . $request->file('image3')->store('images', 's3');
        }
        $updated = $this->products->update($id, $validated);

        return $updated;
    }

    /**
     * Remove the specified product by ID for seller.
     * This method allows the authenticated user to delete a product by its ID.
     * it is not yet implemented in the repository.
     * @authenticated
     */
    public function destroy($id)
    {
        return $this->products->delete($id);
    }
}