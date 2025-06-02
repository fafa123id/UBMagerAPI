<?php

namespace App\Documentation;

/**
 * @OA\Tag(
 *     name="Product",
 *     description="API Endpoints for managing products"
 * )
 * 
 * @OA\Schema(
 *     schema="ProductResource",
 *     title="Product Resource",
 *     description="Product resource representation",
 *     @OA\Property(property="id", type="integer", format="int64", description="Product ID"),
 *     @OA\Property(property="name", type="string", description="Product name"),
 *     @OA\Property(property="type", type="string", description="Product type"),
 *     @OA\Property(property="category", type="string", description="Product category"),
 *     @OA\Property(property="description", type="string", description="Product description"),
 *     @OA\Property(property="quantity", type="integer", minimum=0, description="Product quantity"),
 *     @OA\Property(property="price", type="number", format="float", minimum=0, description="Product price"),
 *     @OA\Property(property="status", type="string", enum={"available","unavailable"}, description="Product status"),
 *     @OA\Property(property="owner", type="string", nullable=true, description="Name of product owner"),
 *     @OA\Property(property="image1", type="string", description="Primary product image URL"),
 *     @OA\Property(property="image2", type="string", nullable=true, description="Secondary product image URL"),
 *     @OA\Property(property="image3", type="string", nullable=true, description="Tertiary product image URL"),
 *     @OA\Property(property="rating", type="number", format="float", description="Average product rating"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp")
 * )
 *
 * @OA\Get(
 *     path="/api/product",
 *     operationId="getProducts",
 *     tags={"Product"},
 *     summary="Get all products",
 *     description="Retrieve all products with optional filtering by type, category, and search query",
 *     @OA\Parameter(
 *         name="type",
 *         in="query",
 *         description="Filter by product type (comma-separated for multiple)",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="category",
 *         in="query",
 *         description="Filter by product category (comma-separated for multiple)",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         description="Search query for product name or owner name",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Products retrieved successfully",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="data",
 *                         type="array",
 *                         @OA\Items(ref="#/components/schemas/ProductResource")
 *                     )
 *                 )
 *             }
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/product/{id}",
 *     operationId="getProduct",
 *     tags={"Product"},
 *     summary="Get product by ID",
 *     description="Retrieve a specific product by its ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Product ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product retrieved successfully",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="data",
 *                         ref="#/components/schemas/ProductResource"
 *                     )
 *                 )
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product not found",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/product-type",
 *     operationId="getProductTypes",
 *     tags={"Product"},
 *     summary="Get all product types",
 *     description="Retrieve all available product types",
 *     @OA\Response(
 *         response=200,
 *         description="Product types retrieved successfully",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(type="string")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/product-category/{type}",
 *     operationId="getProductCategories",
 *     tags={"Product"},
 *     summary="Get categories by product type",
 *     description="Retrieve all categories for a specific product type",
 *     @OA\Parameter(
 *         name="type",
 *         in="path",
 *         description="Product type",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product categories retrieved successfully",
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
 *             allOf={
 *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="data",
 *                         ref="#/components/schemas/ProductResource"
 *                     )
 *                 )
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Seller access required",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/product/{id}",
 *     operationId="updateProduct",
 *     tags={"Product"},
 *     summary="Update a product",
 *     description="Update an existing product (seller only)",
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
 *             allOf={
 *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="data",
 *                         ref="#/components/schemas/ProductResource"
 *                     )
 *                 )
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product not found",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Seller access required",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/product/{id}",
 *     operationId="deleteProduct",
 *     tags={"Product"},
 *     summary="Delete a product",
 *     description="Delete an existing product (seller only)",
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
 *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product not found",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Seller access required",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 */
class ProductDocumentation
{
    // This class is only for documentation purposes
}