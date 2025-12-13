<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

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
        'username',
        'bio',
        'email',
        'password',
        'role_id',
        'status',
        'phone',
        'address',
        'image',
        'email_verified_at',
        'google_id',
        'gmail',
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
    public function getRatingFromAllProduct()
    {
        return DB::table('ratings')
            ->join('products', 'products.id', '=', 'ratings.product_id')
            ->where('products.user_id', $this->id)
            ->where('ratings.rating', '>', 0)
            ->avg('ratings.rating') ?? 0;
    }
    public function findForPassport(string $username)
    {
        return $this->where('email', $username)
            ->orWhere('username', $username)
            ->first();
    }
}
