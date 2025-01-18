<?php

namespace App\Listeners;

use App\AppLog;
use App\Events\RegisterAppLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaveLogOnDB
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
     * @param  RegisterAppLog  $event
     * @return void
     */
    public function handle(RegisterAppLog $event)
    {
        DB::beginTransaction();
        try {
            // Guardar el log en la base de datos
            $log = AppLog::create([
                'type' => $event->type,
                'description' => $event->description,
                'http_code' => $event->httCode,
                'reference' => $event->reference,
                'amount' => $event->amount,
                'bank_code' => $event->bankCode,
                'phone_number' => $event->phone_number,
                'user_id' => $event->user_id,
                'chofer_id' => $event->chofer_id,
                'created_at' => now(),
            ]);

            DB::commit();
            return $log;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error al guardar el log en la base de datos: ' . $th->getMessage());

            // Guardar el log del error en la base de datos
            AppLog::create([
                'type' => 'error',
                'description' => 'Error al guardar el log en la base de datos: ' . $th->getMessage(),
                'http_code' => 500,
                'reference' => $event->reference,
                'amount' => $event->amount,
                'bank_code' => $event->bankCode,
                'phone_number' => $event->phone_number,
                'user_id' => $event->user_id,
                'chofer_id' => $event->chofer_id,
                'created_at' => now(),
            ]);
        }
    }
}
