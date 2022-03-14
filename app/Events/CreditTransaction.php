<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreditTransaction
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $client;
    public $driver;
    public $otherUser;
    public $amount;
    public $transaction;
    public $invoice;
    public $tickets_amount;
 
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($client, $driver,$amount,$transaction = 'ADD',$tickets_amount,$otherUser = null)
    {
        $this->invoice = substr(strtotime(now()),3) . rand(10000,99999);
        $this->client= $client;
        $this->driver = $driver;
        $this->otherUser = $otherUser;
        $this->amount = $amount;
        $this->transaction = $transaction;
        $this->tickets_amount = $tickets_amount;    
    }



   
}
