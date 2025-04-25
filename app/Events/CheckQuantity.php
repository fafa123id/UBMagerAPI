<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckQuantity
{
    use Dispatchable, SerializesModels;
    public $product, $amount;
    /**
     * Create a new event instance.
     */
    public function __construct($product, $amount)
    {
        $this->product = $product;
        $this->amount = $amount;
    }

}
