<?php

namespace App\Http\Controllers\panel;

use App\TransporteTarifa;
use App\UserLineaTransporte;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LineaTransporteController extends Controller
{
    public function index()
    {
        return view('panel.lineaTransporte.index');
    }


    public function getlineasTransporte()
    {
        $lineasTransporte = UserLineaTransporte::with('tarifas:id,tarifa,linea_id', 'users')->get(); 
        return response()->json($lineasTransporte);
    }

    public function guardarLinea(Request $request){
        $linea = new UserLineaTransporte();
        $linea->linea_nombre = $request->linea_nombre;
        $linea->save();
        foreach ($request->lineaTarifas as $key => $tarifa) {
            $lineaTarifa = new TransporteTarifa();
            $lineaTarifa->linea_id = $linea->id;
            $lineaTarifa->tarifa = floatval($tarifa);
            $lineaTarifa->save();
        }

        return response()->json([
            'message'=>'Linea de transporte guardada exitosamente',
            'linea'=>$linea,
        ]);
    }
}
