<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\successReturn;
use App\Repositories\Abstract\ProductRepositoryInterface;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Product",
 *     description="API Endpoints for managing products"
 * )
 * @OA\Schema(  
 *     schema="Product",
 *     required={"name", "type", "category", "description", "price"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Product ID"),
 *     @OA\Property(property="name", type="string", description="Product name"),
 *     @OA\Property(property="type", type="string", description="Product type"),
 *     @OA\Property(property="category", type="string", description="Product category"),
 *     @OA\Property(property="description", type="string", description="Product description"),
 *     @OA\Property(property="quantity", type="integer", description="Product quantity in stock"),
 *     @OA\Property(property="price", type="number", format="float", description="Product price"),
 *     @OA\Property(property="status", type="string", enum={"available","unavailable"}, description="Product availability status"),
 *     @OA\Property(property="owner", type="string", nullable=true, description="Name of product owner"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp"),
 * )
 * 
 */
class ProductController extends Controller
{
    protected $products;

    public function __construct(ProductRepositoryInterface $products)
    {
        $this->products = $products;
    }

    /**
     * @OA\Get(
     *     path="/api/product",
     *     operationId="getProductsList",
     *     tags={"Product"},
     *     summary="Get list of products",
     *     description="Returns list of products with optional filtering by type, category, and search query",
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by product type(s), comma-separated. Use 'all' for all types",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter by product category(ies), comma-separated. Use 'all' for all categories",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Search query string",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=true),
     *             @OA\Property(property="message", type="string", example="Product retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
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
                'data' => ProductResource::collection($this->products->all($queries, $typeList, $categoryList)),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/api/product/{id}",
     *     operationId="getProductById",
     *     tags={"Product"},
     *     summary="Get product information",
     *     description="Returns product data for a specific ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=true),
     *             @OA\Property(property="message", type="string", example="Product retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function show($id)
    {
        return $this->products->find($id);
    }

    /**
     * @OA\Get(
     *     path="/api/product-type",
     *     operationId="getProductTypes",
     *     tags={"Product"},
     *     summary="Get all product types",
     *     description="Returns a list of all product types available",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getType()
    {
        return $this->products->getType();
    }

    /**
     * @OA\Get(
     *     path="/api/product-category/{type}",
     *     operationId="getCategoriesByType",
     *     tags={"Product"},
     *     summary="Get product categories by type",
     *     description="Returns a list of product categories for a specific product type",
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         description="Product type",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Type not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function getCategoryByType($type)
    {
        return $this->products->getCategoryByType($type);
    }

    /**
     * @OA\Post(
     *     path="/api/product",
     *     operationId="storeProduct",
     *     tags={"Product"},
     *     summary="Create a new product",
     *     security={{"bearerAuth":{}}},
     *     description="Stores a new product in the database",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "type", "category", "description", "price"},
     *             @OA\Property(property="name", type="string", example="Product Name"),
     *             @OA\Property(property="type", type="string", example="electronics"),
     *             @OA\Property(property="category", type="string", example="smartphone"),
     *             @OA\Property(property="description", type="string", example="Product description"),
     *             @OA\Property(property="quantity", type="integer", example=10),
     *             @OA\Property(property="price", type="number", format="float", example=599.99),
     *             @OA\Property(property="status", type="string", enum={"available", "unavailable"}, example="available")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=true),
     *             @OA\Property(property="message", type="string", example="Product created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
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
        ]);
        return $this->products->create($validated);
    }

    /**
     * @OA\Put(
     *     path="/api/product/{id}",
     *     operationId="updateProduct",
     *     tags={"Product"},
     *     summary="Update an existing product",
     *     security={{"bearerAuth":{}}},
     *     description="Updates a product's information",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Product Name"),
     *             @OA\Property(property="type", type="string", example="electronics"),
     *             @OA\Property(property="category", type="string", example="laptop"),
     *             @OA\Property(property="description", type="string", example="Updated product description"),
     *             @OA\Property(property="quantity", type="integer", example=15),
     *             @OA\Property(property="price", type="number", format="float", example=899.99),
     *             @OA\Property(property="status", type="string", enum={"available", "unavailable"}, example="available")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=true),
     *             @OA\Property(property="message", type="string", example="Product updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
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
        ]);

        $updated = $this->products->update($id, $validated);

        return $updated;
    }

    /**
     * @OA\Delete(
     *     path="/api/product/{id}",
     *     operationId="deleteProduct",
     *     tags={"Product"},
     *     summary="Delete product",
     *     security={{"bearerAuth":{}}},
     *     description="Deletes a product by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function destroy($id)
    {
        return $this->products->delete($id);
    }
}