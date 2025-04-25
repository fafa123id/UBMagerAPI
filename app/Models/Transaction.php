<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'history_id',
        'total_price',
        'payment_method',
        'status',
        'receipt',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function history()
    {
        return $this->belongsTo(History::class);
    }
}
