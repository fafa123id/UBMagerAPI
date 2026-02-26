<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('seller_rating_count')->default(0);
            $table->unsignedInteger('seller_rating_sum')->default(0);
            $table->decimal('seller_rating_avg', 3, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'seller_rating_count',
                'seller_rating_sum',
                'seller_rating_avg'
            ]);
        });
    }
};