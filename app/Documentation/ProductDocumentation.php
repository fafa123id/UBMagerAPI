<?php
namespace App\Documentation;
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
 *     @OA\Property(property="user_id", type="integer", description="Owner user ID"),
 *     @OA\Property(property="image1", type="string", description="Primary product image URL"),
 *     @OA\Property(property="image2", type="string", nullable=true, description="Secondary product image URL"),
 *     @OA\Property(property="image3", type="string", nullable=true, description="Tertiary product image URL"),
 *     @OA\Property(property="rating", type="number", format="float", description="Average product rating"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp"),
 *     @OA\Property(
 *         property="user",
 *         description="Product owner information",
 *         ref="#/components/schemas/UserResource"
 *     ),
 *     @OA\Property(
 *         property="orders",
 *         type="array",
 *         description="Orders for this product",
 *         @OA\Items(ref="#/components/schemas/Order")
 *     ),
 *     @OA\Property(
 *         property="nego",
 *         type="array",
 *         description="Negotiations for this product",
 *         @OA\Items(ref="#/components/schemas/Nego")
 *     ),
 *     @OA\Property(
 *         property="ratings",
 *         type="array",
 *         description="Ratings for this product",
 *         @OA\Items(ref="#/components/schemas/Rating")
 *     )
 * )
 * @OA\Property(property="owner", type="string", nullable=true, description="Name of product owner"),
 * @OA\Property(property="image1", type="string", description="Primary product image URL"),
 * @OA\Property(property="image2", type="string", nullable=true, description="Secondary product image URL"),
 * @OA\Property(property="image3", type="string", nullable=true, description="Tertiary product image URL"),
 * @OA\Property(property="rating", type="number", format="float", description="Average product rating"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp"),
 * )
 * 
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
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Product retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Product")
 *             )
 *         )
 *     )
 * )
 *
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
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/Product")
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/product-type",
 *     operationId="getProductTypes",
 *     tags={"Product"},
 *     summary="Get all product types",
 *     description="Returns list of all available product types",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(type="string")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/product-category/{type}",
 *     operationId="getProductCategoriesByType",
 *     tags={"Product"},
 *     summary="Get product categories by type",
 *     description="Returns list of categories for a specific product type",
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
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/product",
 *     operationId="storeProduct",
 *     tags={"Product"},
 *     summary="Create a new product",
 *     description="Store a newly created product for seller",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"name", "type", "category", "description", "price", "image1"},
 *                 @OA\Property(property="name", type="string", description="Product name"),
 *                 @OA\Property(property="type", type="string", description="Product type"),
 *                 @OA\Property(property="category", type="string", description="Product category"),
 *                 @OA\Property(property="description", type="string", description="Product description"),
 *                 @OA\Property(property="quantity", type="integer", minimum=0, description="Product quantity"),
 *                 @OA\Property(property="price", type="number", format="float", minimum=0, description="Product price"),
 *                 @OA\Property(property="status", type="string", enum={"available","unavailable"}, description="Product status"),
 *                 @OA\Property(property="image1", type="string", format="binary", description="Primary product image"),
 *                 @OA\Property(property="image2", type="string", format="binary", description="Secondary product image"),
 *                 @OA\Property(property="image3", type="string", format="binary", description="Tertiary product image")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Product created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Product created successfully"),
 *             @OA\Property(property="data", ref="#/components/schemas/Product")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/product/{id}",
 *     operationId="updateProduct",
 *     tags={"Product"},
 *     summary="Update a product",
 *     description="Update an existing product",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Product ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="name", type="string", description="Product name"),
 *                 @OA\Property(property="type", type="string", description="Product type"),
 *                 @OA\Property(property="category", type="string", description="Product category"),
 *                 @OA\Property(property="description", type="string", description="Product description"),
 *                 @OA\Property(property="quantity", type="integer", minimum=0, description="Product quantity"),
 *                 @OA\Property(property="price", type="number", format="float", minimum=0, description="Product price"),
 *                 @OA\Property(property="status", type="string", enum={"available","unavailable"}, description="Product status"),
 *                 @OA\Property(property="image1", type="string", format="binary", description="Primary product image"),
 *                 @OA\Property(property="image2", type="string", format="binary", description="Secondary product image"),
 *                 @OA\Property(property="image3", type="string", format="binary", description="Tertiary product image")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Product updated successfully"),
 *             @OA\Property(property="data", ref="#/components/schemas/Product")
 *         )
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/product/{id}",
 *     operationId="deleteProduct",
 *     tags={"Product"},
 *     summary="Delete a product",
 *     description="Delete an existing product",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Product ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Product deleted successfully")
 *         )
 *     )
 * )
 */
class ProductDocumentation
{
}