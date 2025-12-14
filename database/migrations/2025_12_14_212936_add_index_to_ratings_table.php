<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {

            // PENTING: untuk AVG / COUNT per product
            $table->index('product_id', 'idx_ratings_product_id');

            // Mencegah user rating produk yang sama berkali-kali
            $table->unique(['user_id', 'product_id'], 'uq_ratings_user_product');

            // Opsional: bantu query agregasi
            $table->index(['product_id', 'rating'], 'idx_ratings_product_rating');
        });
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropIndex('idx_ratings_product_id');
            $table->dropUnique('uq_ratings_user_product');
            $table->dropIndex('idx_ratings_product_rating');
        });
    }
};
