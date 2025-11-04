<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class userResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'bio' => $this->bio,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address ?? null,
            'image' => config('filesystems.disks.s3.url') . $this->image ?? 'https://static.vecteezy.com/system/resources/thumbnails/020/765/399/small/default-profile-account-unknown-icon-black-silhouette-free-vector.jpg',
            'role_id' => $this->role_id,
            'is_verified' => $this->email_verified_at ? true : false,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'rating' => $this->role_id == 1 ? (float) $this->getRatingFromAllProduct() : null,
        ];
    }
}
