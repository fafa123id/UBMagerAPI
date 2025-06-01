<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'product_id',
        'quantity',
        'total_price',
        'status',
        'address',
        'is_rated',
    ];
    protected $casts = [
        'price' => 'decimal:2'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class); // ✅ relasi balik ke transaksi
    }

    public function product()
    {
        return $this->belongsTo(Product::class); // ✅ pakai belongsTo, bukan hasOne
    }
    public function isRated()
    {
        return $this->is_rated; // ✅ mengembalikan status apakah sudah di-rating
    }
}

