<?php

namespace App\Console\Commands;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalculateSellerRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recalculate-seller-ratings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Recalculate seller ratings...");

        $stats = Rating::join('products', 'ratings.product_id', '=', 'products.id')
            ->select(
                'products.user_id as seller_id',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(ratings.rating) as sum'),
                DB::raw('AVG(ratings.rating) as avg')
            )
            ->groupBy('products.user_id')
            ->get();

        foreach ($stats as $stat) {
            User::where('id', $stat->seller_id)->update([
                'seller_rating_count' => $stat->count,
                'seller_rating_sum'   => $stat->sum,
                'seller_rating_avg'   => round($stat->avg, 2),
            ]);
        }

        $this->info("Done 🎉");
    }
}
