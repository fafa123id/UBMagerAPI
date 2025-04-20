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
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'status' => $this->status,
            'owner' => $this->user->name ?? null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}

