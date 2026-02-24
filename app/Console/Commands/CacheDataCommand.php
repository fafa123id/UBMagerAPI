<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\Rating;

class CacheDataCommand extends Command
{
    protected $signature = 'analytics:cache';
    protected $description = 'Warm cache analytics homepage';

    public function handle()
    {
        $this->info('Generating analytics cache...');

        $data = $this->generateAnalytics();

        // simpan cache 5 menit (300 detik)
        Cache::put('analytics:home:v1', $data, 360);

        $this->info('Analytics cache updated!');
    }

        private function generateAnalytics()
    {
        $top6Products = Product::query()
            ->joinSub(
                Rating::query()
                    ->selectRaw('product_id, AVG(rating) as rating_avg, COUNT(*) as rating_count')
                    ->groupBy('product_id'),
                'r',
                fn($join) => $join->on('products.id', '=', 'r.product_id')
            )
            ->where('products.status', 'available')
            ->orderByDesc('r.rating_count')
            ->limit(6)
            ->get([
                'products.id',
                'products.name',
                'products.price',
                'products.category',
                'products.type',
                'products.image1',
                'products.image2',
                'products.image3',
                'r.rating_avg',
                'r.rating_count',
            ]);

        $top8Categories = Product::query()
            ->where('status', 'available')
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $top6Products = $top6Products->values()->map(function ($item, $i) {
            $item->no = $i;
            return $item;
        });

        $top8Categories = $top8Categories->values()->map(function ($item, $i) {
            $item->no = $i;
            return $item;
        });

        return [
            'message' => 'Analytics data',
            'top_products' => $top6Products,
            'top_categories' => $top8Categories,
        ];
    }
}