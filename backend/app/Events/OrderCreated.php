<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $analytics;
    //public $recommendations;


    public function __construct($order, $analytics)
    {
        $this->order = $order;
        $this->analytics = $analytics;
    }

    public function broadcastOn()
    {
        return new Channel('orders'); 
    }

    public function broadcastAs()
    {
        return 'order_created';
    }

    public function broadcastWith()
    {
        return [
            'order' => $this->order,
            'analytics' => $this->analytics,
        ];
    }
}
