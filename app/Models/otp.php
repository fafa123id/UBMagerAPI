<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class otp extends Model
{
    use Prunable;

    public function prunable()
    {
        return static::where('expires_at', '<', now());
    }
    //create the model otp base on contrroller  
    protected $fillable = [
        'email',
        'otp',
        'status',
        'expires_at',
    ];
    protected $casts = [
        'expires_at' => 'datetime',
    ];
    
}
