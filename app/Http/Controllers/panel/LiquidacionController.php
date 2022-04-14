<?php

namespace App\Http\Controllers\panel;

use App\Http\Controllers\Controller;
use App\Liquidacion;
use Illuminate\Http\Request;

class LiquidacionController extends Controller
{
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
