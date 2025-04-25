<?php

namespace App\Listeners;

use App\Events\CheckAmount;

class CheckAmountListener
{

    /**
     * Handle the event.
     */
    public function handle(CheckAmount $event): int
    {
        if (in_array($event->product->type, ['Jastip', 'Aplikasi Premium','Pengiriman','Bebersih'])) {
            return 1;
        }
        return $event->amount;
    }
}
