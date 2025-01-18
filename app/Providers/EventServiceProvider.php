<?php

namespace App\Providers;

use App\Events\CreditTransaction;
use App\Events\RecargaUserWallet;
use App\Events\RegisterAppLog;
use App\Listeners\SaveBankTransaction;
use App\Listeners\SaveLogOnDB;
use App\Listeners\TransactionDone;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CreditTransaction::class => [
            TransactionDone::class,
        ],
        RecargaUserWallet::class => [
            SaveBankTransaction::class
        ],
        RegisterAppLog::class => [
            SaveLogOnDB::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
