<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'type', 'category', 'quantity', 'price', 'description', 'status', 'user_id','image1', 'image2', 'image3'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function nego()
    {
        return $this->hasMany(Nego::class);
    }
    public function isNegotiable()
    {
        return $this->quantity > 0 ;
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    protected $casts = [
        'price' => 'decimal:2'
    ];
    public function getRattingAttribute()
    {
        if ($this->relationLoaded('ratings') && $this->ratings->isNotEmpty()) {
            return (float) $this->ratings->avg('rating');
        }
        return 0.0;
    }

}


