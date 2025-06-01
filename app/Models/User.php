<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status',
        'phone',
        'address',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function resettoken()
    {
        return $this->hasOne(ResetToken::class);
    }
    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
    public function order()
    {
        return $this->hasMany(Order::class);
    }
    public function ordersThroughProducts()
    {
        return $this->hasManyThrough(Order::class, Product::class, 'user_id', 'product_id', 'id', 'id');
    }
    public function receivedNegos()
    {
        return $this->hasManyThrough(Nego::class, Product::class, 'user_id', 'product_id');
    }
    public function nego()
    {
        return $this->hasMany(Nego::class);
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
