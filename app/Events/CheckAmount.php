<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckAmount
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
