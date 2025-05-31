<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'type', 'category', 'quantity', 'price', 'description', 'status', 'user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    protected $casts = [
        'price' => 'decimal:2'
    ];

}


