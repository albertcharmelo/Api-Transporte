<?php

namespace App\Console\Commands;

use App\Recarga;
use App\UserRecarga;
use Illuminate\Console\Command;

class BuscarReferencias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '20minute:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cotejar referencias de las tablas banco_recargas y user_recargas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $recargas = Recarga::all();  
        
        foreach ($recargas as $key => $baco_recarga) {
            $user_recarga = UserRecarga::where('referencia',$baco_recarga->referencia)->first();
            if($user_recarga && $user_recarga->estado == 'En espera'){
                $user_recarga->user->wallet->creditos = $user_recarga->user->wallet->creditos + $baco_recarga->monto;
                $user_recarga->user->wallet->save();
                $user_recarga->estado = 'Aceptado';
                $user_recarga->save();
                $baco_recarga->estado = 'Aceptado';
                $baco_recarga->save();

            }
        }

        $this->info('Referencias checkeadas satisfactoriamente');
    }
}
