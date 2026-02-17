<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class OrderUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $orderId;
    public $storeId;

    public function __construct($orderId, $storeId)
    {
        $this->orderId = $orderId;
        $this->storeId = $storeId;
    }

    public function broadcastOn()
    {
        return new Channel('store.' . $this->storeId);
    }

    public function broadcastAs()
    {
        return 'OrderUpdated';
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->orderId,
            'store_id' => $this->storeId,
        ];
    }
}
