<?php

namespace App\Listeners;

use App\Events\CreditTransaction;
use App\UserTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TransactionDone
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreditTransaction $event)
    {
        
        $trasacción = UserTransaction::create([
            'driver_id'=> $event->driver,
            'client_id'=> $event->client,
            'other_user_id'=> $event->otherUser,
            'amount'=> $event->amount,
            'transaction' => $event->transaction,
            'invoice'=>$event->invoice,
            'tickets_amount'=>$event->tickets_amount,
        ]);
        
        return $trasacción;

    }
}
