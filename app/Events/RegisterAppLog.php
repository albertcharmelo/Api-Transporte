<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegisterAppLog
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $type;
    public $description;
    public $httCode;
    public $reference;
    public $amount;
    public $bankCode;
    public $phone_number;
    public $user_id;
    public $chofer_id;


    public function __construct(
        String $type,
        String $description,
        Int $httCode,
        String $reference,
        Float $amount,
        String $bankCode,
        String $phone_number,
        Int $user_id,
        Int $chofer_id
    ) {
        $this->type = $type;
        $this->description = $description;
        $this->httCode = $httCode;
        $this->reference = $reference;
        $this->amount = $amount;
        $this->bankCode = $bankCode;
        $this->phone_number = $phone_number;
        $this->user_id = $user_id;
        $this->chofer_id = $chofer_id;
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
