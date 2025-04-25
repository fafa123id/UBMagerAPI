<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class successReturn extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => true,
            'message' => $this->resource['message'],
            'data' => $this->resource['data'],
        ];
    }
    public function withResponse($request, $response)
    {
        if (isset($this->resource['status'])) {
            $response->setStatusCode($this->resource['status']);
        }
    }
}
