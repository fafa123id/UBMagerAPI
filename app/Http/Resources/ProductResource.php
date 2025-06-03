<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'category'=> $this->category,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => (float) $this->price,
            'status' => $this->status,
            'owner' => $this->user ? $this->user->name : null,
            'image1' => $this->image1,
            'image2' => $this->image2 ?? null,
            'image3' => $this->image3 ?? null,
            'rating' => (float) $this->getRattingAttribute(),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at'=> $this->updated_at->toDateTimeString(),
        ];
    }
}

