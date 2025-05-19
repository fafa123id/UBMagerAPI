<?php
namespace App\Documentation;
/**
 * @OA\Tag(
 *     name="Cart",
 *     description="API Endpoints for shopping cart management"
 * )
 * 
 * @OA\Schema(
 *     schema="Cart",
 *     required={"id", "user_name", "product_name", "product_category", "product_type", "price", "amount", "total_price"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Cart item ID"),
 *     @OA\Property(property="user_name", type="string", description="Name of the user who owns this cart item"),
 *     @OA\Property(property="product_name", type="string", description="Name of the product in cart"),
 *     @OA\Property(property="product_category", type="string", description="Category of the product"),
 *     @OA\Property(property="product_type", type="string", description="Type of the product"),
 *     @OA\Property(property="price", type="number", format="float", description="Price of the product"),
 *     @OA\Property(property="amount", type="integer", description="Quantity of product in cart"),
 *     @OA\Property(property="total_price", type="number", format="float", description="Total price (price × amount)"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp")
 * )
 * 
 * 
 

 * @OA\Get(
 *     path="/api/cart",
 *     operationId="getCartItems",
 *     tags={"Cart"},
 *     summary="Get all items in user's cart",
 *     description="Returns a list of all items in the authenticated user's cart",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Cart retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Cart")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Authentication required"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 

 * @OA\Get(
 *     path="/api/cart/{id}",
 *     operationId="getCartItem",
 *     tags={"Cart"},
 *     summary="Get specific cart item",
 *     description="Returns a specific cart item by ID",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Cart item ID",
 *         required=true,
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/Cart")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Cart item not found"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Authentication required"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 


 * @OA\Post(
 *     path="/api/cart",
 *     operationId="addToCart",
 *     tags={"Cart"},
 *     summary="Add item to cart",
 *     description="Adds a product to the user's cart",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"product_id", "amount"},
 *             @OA\Property(property="product_id", type="integer", example=1, description="Product ID to add to cart"),
 *             @OA\Property(property="amount", type="integer", example=1, description="Quantity to add")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Item added to cart successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Cart")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Authentication required"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 


 * @OA\Put(
 *     path="/api/cart/{id}",
 *     operationId="updateCartItem",
 *     tags={"Cart"},
 *     summary="Update cart item quantity",
 *     description="Updates the quantity of a specific item in the cart",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Cart item ID",
 *         required=true,
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"amount"},
 *             @OA\Property(property="amount", type="integer", example=3, description="New quantity")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cart item updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Cart")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Authentication required"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Cart item not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 


 * @OA\Post(
 *     path="/api/cart/{id}/add-one",
 *     operationId="incrementCartItem",
 *     tags={"Cart"},
 *     summary="Increment cart item by one",
 *     description="Increases the quantity of a specific cart item by one",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Cart item ID",
 *         required=true,
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cart item quantity increased successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Cart")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Authentication required"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Cart item not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 

 * @OA\Post(
 *     path="/api/cart/{id}/remove-one",
 *     operationId="decrementCartItem",
 *     tags={"Cart"},
 *     summary="Decrement cart item by one",
 *     description="Decreases the quantity of a specific cart item by one. If quantity becomes zero, removes the item completely",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Cart item ID",
 *         required=true,
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cart item quantity decreased or item removed successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Cart")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Authentication required"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Cart item not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 

 * @OA\Delete(
 *     path="/api/cart/{id}",
 *     operationId="removeCartItem",
 *     tags={"Cart"},
 *     summary="Remove item from cart",
 *     description="Removes a specific item from the cart completely",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Cart item ID",
 *         required=true,
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cart item removed successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Cart deleted")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Authentication required"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Cart item not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Cart not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 * @OA\Delete(
 *     path="/api/cart",
 *     operationId="clearCart",
 *     tags={"Cart"},
 *     summary="Clear entire cart",
 *     description="Removes all items from the user's cart",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Cart cleared successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="All cart deleted")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Authentication required"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
class CartDocumentation {}