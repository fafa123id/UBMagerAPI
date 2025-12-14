<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('rating_count')->default(0)->after('image3');
            $table->decimal('rating_avg', 3, 2)->default(0)->after('rating_count'); // 0.00 - 9.99 (rating 1-5 aman)
            $table->unsignedInteger('rating_sum')->default(0)->after('rating_avg');  // opsional tapi bikin update cepat
            $table->index(['status', 'rating_avg', 'rating_count']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['status', 'rating_avg', 'rating_count']);
            $table->dropColumn(['rating_count', 'rating_avg', 'rating_sum']);
        });
    }
};
