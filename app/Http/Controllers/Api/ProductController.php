<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\successReturn;
use App\Repositories\Abstract\ProductRepositoryInterface;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    protected $products;

    public function __construct(ProductRepositoryInterface $products)
    {
        $this->products = $products;
    }

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
                'data' => ProductResource::collection($this->products->all($queries, $typeList, $categoryList)),
            ]
        );
    }

    public function show($id)
    {
        return $this->products->find($id);
    }

    
    public function getType()
    {
        return $this->products->getType();
    }


    public function getCategoryByType($type)
    {
        return $this->products->getCategoryByType($type);
    }

    
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
        ]);
        return $this->products->create($validated);
    }

    
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
        ]);

        $updated = $this->products->update($id, $validated);

        return $updated;
    }

    
    public function destroy($id)
    {
        return $this->products->delete($id);
    }
}