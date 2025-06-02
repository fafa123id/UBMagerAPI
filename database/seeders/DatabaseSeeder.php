<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin 1',
            'email' => 'seller@admin.test',
            'phone' => '08123456789',
            'password' => Hash::make('password123'),
            'role_id' => 1,
            'address' => 'Jl. Seller No. 1, Malang',
            'image' => 'https://ui-avatars.com/api/?name=Admin+1&background=random&color=fff',
            'email_verified_at' => now(),
            'status' => 'verified',
        ]);
        User::create([
            'name' => 'Admin 2',
            'email' => 'seller2@admin.test',
            'phone' => '08123456781',
            'password' => Hash::make('password123'),
            'role_id' => 1,
            'address' => 'Jl. Seller No. 2, Malang',
            'image' => 'https://ui-avatars.com/api/?name=Admin+2&background=random&color=fff',
            'email_verified_at' => now(),
            'status' => 'verified',
        ]);
        User::create([
            'name' => 'User 1',
            'email' => 'buyer@user.test',
            'phone' => '08123456780',
            'password' => Hash::make('password123'),
            'role_id' => 0,
            'address' => 'Jl. User No. 1, Malang',
            'image' => 'https://ui-avatars.com/api/?name=User+1&background=random&color=fff',
            'email_verified_at' => now(),
            'status' => 'verified',
        ]);
        User::create([
            'name' => 'Scribe Test',
            'email' => 'scribe@test',
            'phone' => '08123456782',
            'password' => Hash::make('password123'),
            'role_id' => 1,
            'address' => 'Jl. Scribe No. 1, Malang',
            'image' => 'https://ui-avatars.com/api/?name=Scribe+Test&background=random&color=fff',
            'email_verified_at' => now(),
            'status' => 'verified',
        ]);

        $user1 = User::where('email', 'seller@admin.test')->first();
        $user1->product()->create([
            'name' => 'Product 1',
            'type' => 'Electronics',
            'category' => 'Gadget',
            'quantity' => 10,
            'price' => 1000000,
            'description' => 'This is a description for Product 1.',
            'status' => 'available',
            'image1' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/14/Product_sample_icon_picture.png/640px-Product_sample_icon_picture.png',
        ]);
        $user1->product()->create([
            'name' => 'Product 2',
            'type' => 'Clothing',
            'category' => 'Fashion',
            'quantity' => 5,
            'price' => 200000,
            'description' => 'This is a description for Product 2.',
            'status' => 'available',
            'image1' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/14/Product_sample_icon_picture.png/640px-Product_sample_icon_picture.png',
        ]);


        $user2 = User::where('email', 'buyer@user.test')->first();
        $user3 = User::where('email', 'scribe@test')->first();
        $user3->product()->create([
            'name' => 'Product 3',
            'type' => 'Clothing',
            'category' => 'Fashion',
            'quantity' => 5,
            'price' => 200000,
            'description' => 'This is a description for Product 3.',
            'status' => 'available',
            'image1' => 'URL_ADDRESS.wikimedia.org/wikipedia/commons/thumb/1/14/Product_sample_icon_picture.png/640px-Product_sample_icon_picture.png',
        ]);
        $user3->transaction()->create([
            'user_id' => $user3->id,
            'total_price' => 1000000,
        ]);
        $user3->order()->create([
            'user_id' => $user3->id,
            'product_id' => 3,
            'transaction_id' => 1,
            'quantity' => 1,
            'total_price' => 1000000,
            'address' => 'Jl. User No. 1, Malang',
            'status' => 'pending',
        ]);
        $user3->nego()->create([
            'user_id' => $user3->id,
            'product_id' => 1,
            'nego_price' => 1000000,
        ]);
        $user2->transaction()->create([
            'user_id' => $user2->id,
            'total_price' => 1000000,
        ]);
        $user2->order()->create([
            'user_id' => $user2->id,
            'product_id' => 3,
            'transaction_id' => 2,
            'total_price' => 1000000,
            'address' => 'Jl. User No. 1, Malang',

        ]);
        $user2->nego()->create([
            'user_id' => $user2->id,
            'product_id' => 3,
            'nego_price' => 1000000,
            'status' => 'pending',
        ]);
        Rating::create([
            'user_id' => $user2->id,
            'product_id' => 3,
            'rating' => 4,
            'comment' => 'Great product!',
        ]);
        Rating::create([
            'user_id' => $user2->id,
            'product_id' => 3,
            'rating' => 1,
            'comment' => 'bad quality!',
        ]);
        Rating::create([
            'user_id' => $user2->id,
            'product_id' => 3,
            'rating' => 5,
            'comment' => 'bad quality!',
        ]);
        Rating::create([
            'user_id' => $user2->id,
            'product_id' => 3,
            'rating' => 3,
            'comment' => 'bad quality!',
        ]);
    }
}
