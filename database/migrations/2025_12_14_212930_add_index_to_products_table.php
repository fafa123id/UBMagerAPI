<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // Filter type + order quantity
            $table->index(['type', 'quantity'], 'idx_products_type_quantity');

            // Filter category + order quantity
            $table->index(['category', 'quantity'], 'idx_products_category_quantity');

            // Filter type + category + order quantity (kombinasi paling sering)
            $table->index(['type', 'category', 'quantity'], 'idx_products_type_category_quantity');

            // Kalau kamu pakai status = available
            $table->index(['status', 'type', 'category', 'quantity'], 'idx_products_status_type_category_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_type_quantity');
            $table->dropIndex('idx_products_category_quantity');
            $table->dropIndex('idx_products_type_category_quantity');
            $table->dropIndex('idx_products_status_type_category_quantity');
        });
    }
};
