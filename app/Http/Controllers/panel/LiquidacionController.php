<?php

namespace App\Http\Controllers\panel;

use App\Liquidacion;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\DB;
use App\Exports\LiquidacionesExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class LiquidacionController extends Controller
{
    
    public function index(){
        $comision = DB::table('comisiones')->where('id', '=', 1)->get()->first();
        
        return view('panel.liquidacion.index')->with(compact('comision'));
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

    public static function updateComision(Request $request){
       
        $comision = DB::table('comisiones')->where('id', '=', 1)->update([
            'comision'=> floatval($request->comision)
        ]);
      
      

        return response()->json(['success' => 'Comision actualizada correctamente']);


    }
}
