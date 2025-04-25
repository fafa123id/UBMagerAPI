<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return 
        [
            "id"=> $this->id,
            "user_name" => $this->user->name,
            "product_name" => $this->product->name, 
            "product_category"=> $this->product->category,
            "product_type"=> $this->product->type,
            "price"=> $this->product->price,
            "amount"=> $this->amount,
            "total_price"=> $this->product->price * $this->amount,
            "created_at"=> $this->created_at->toDateTimeString(),
            "updated_at"=> $this->updated_at->toDateTimeString(),            
        ];
    }
}
