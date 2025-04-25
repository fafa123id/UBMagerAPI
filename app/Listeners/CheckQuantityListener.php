<?php

namespace App\Listeners;

use App\Events\CheckQuantity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckQuantityListener
{

    /**
     * Handle the event.
     */
    public function handle(CheckQuantity $event): bool
    {
        if (in_array($event->product->type, ['Jastip', 'Aplikasi Premium','Pengiriman','Bebersih'])) {
            return true;
        }
        $quantity = $event->product->quantity;
        if ($quantity <= 0 || $event->amount > $quantity) {
            return false;
        } else {
            return true;
        }
    }
}
