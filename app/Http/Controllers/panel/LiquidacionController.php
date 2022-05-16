<?php

namespace App\Http\Controllers\panel;

use App\Exports\LiquidacionesExport;
use App\Http\Controllers\Controller;
use App\Liquidacion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\FuncCall;

class LiquidacionController extends Controller
{
    
    public function index(){
        return view('panel.liquidacion.index');
    }

    public static function getLiquidaciones(Request $request) {
        return Excel::download(new LiquidacionesExport('Exportación de Liquidación'), 'Facturación.xlsx');
    }
    
    public static function LiquidarUsuarios(){
        $liquidacion = Liquidacion::with('user')->get();
        $content = '';
        foreach ($liquidacion as $key => $l) {
            $content .= $l->banco." ".$l->numero_de_cuenta." ".$l->tipo_cuenta." ".$l->cedula." ".$l->monto_liquidar."/n";
        }


        //cretae a txt file with all $liquidacion where filename is today date
        $filename = date('Y-m-d');
        $file = fopen($filename.'.txt', 'w');
        foreach ($liquidacion as $key => $value) {
            fwrite($file, $value->user->full_name."\n");
        }
        fclose($file);

        //save file in public/liquidaciones
        $file = $filename.'.txt';
        $path = public_path('liquidaciones/'.$file);
        $file = file_get_contents($filename.'.txt');
        file_put_contents($path, $file);
    }

    
}
