<?php

namespace App\Console\Commands;

use App\Mail\LiquidacionMail;
use Illuminate\Console\Command;
use App\Exports\LiquidacionesExport;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class LiquidacionExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liquidacion:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear la liquidación diaria';

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
        $filename = 'liquidacion_'.date('Y-m-d').'.xlsx';

        Excel::store(new LiquidacionesExport($filename ), $filename , 'liquidaciones');
        Mail::to('albertcharmelocontacto@gmail.com')->send(new LiquidacionMail($filename));
        $this->info('Liquidaciones creadas');        
    }
}
