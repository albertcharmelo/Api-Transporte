<?php

namespace App\Listeners;

use App\Events\RecargaUserWallet;
use App\UserRecarga;
use App\UserWallet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SaveBankTransaction
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
     * @param  RecargaUserWallet  $event
     * @return void
     */
    public function handle(RecargaUserWallet $event)
    {


        try {
            // Guardar la recarga del usuario
            UserRecarga::create([
                'banco' => $event->bankCode,
                'referencia' => $event->reference,
                'fecha' => now(),
                'user_id' => $event->userId,
            ]);

            // Actualizar el saldo de la wallet del usuario
            $userWallet = UserWallet::where('user_id', $event->userId)->first();



            if ($userWallet) {
                $userWallet->creditos += $event->amount;
                $userWallet->save();
            } else {
                throw new \Exception('Wallet no encontrada para el usuario.');
            }
        } catch (\Throwable $th) {
            // Manejar el error
            Log::error('Error al procesar la recarga del usuario: ' . $th->getMessage());
        }
    }
}
