<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RecargaUserWallet
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $userId;
    public $amount;
    public $bankCode;
    public $reference;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Int $userId, Float $amount, String $bankCode, String $reference)
    {
        $this->userId = $userId;
        $this->amount = $amount;
        $this->bankCode = $bankCode;
        $this->reference = $reference;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
