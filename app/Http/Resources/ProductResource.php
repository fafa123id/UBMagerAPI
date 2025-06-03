<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'type' => $this->type ?? '',
            'category'=> $this->category ?? '',
            'description' => $this->description ?? '',
            'quantity' => (int) $this->quantity,
            'price' => (float) $this->price,
            'status' => $this->status ?? 'available',
            'owner' => $this->user ? $this->user->name : null,
            'image1' => $this->image1,
            'image2' => $this->image2,
            'image3' => $this->image3,
            'rating' => $this->ratings->isNotEmpty() ? (float) $this->ratings->avg('rating') : 0.0,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at'=> $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}

