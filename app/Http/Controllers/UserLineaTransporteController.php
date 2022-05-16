<?php

namespace App\Http\Controllers;

use App\TransporteTarifa;
use App\UserLineaTransporte;
use Illuminate\Http\Request;

class UserLineaTransporteController extends Controller
{
    
    public function getBusLines(Request $request){
        $buslines = UserLineaTransporte::all();
        return response()->json([
            'message'=>'Buses obtenidos exitosamente',
            'buslines'=>$buslines,

        ]);
    }


    public function getBusCollection(Request $request){
       
         $buslines = UserLineaTransporte::where('id',$request->busLineId)
            ->with('users')->get()->first();
            
       
        
        return response()->json([
            'message'=>'Buses obtenidos exitosamente',
            'buslines'=>$buslines,
        ]);
    }

    public function getTarifas(Request $request){
        $tarifas = TransporteTarifa::where('linea_id',$request->busLineId)->get();
        return response()->json([
            'message'=>'Tarifas obtenidas exitosamente',
            'tarifas'=>$tarifas,
        ]);
    }


    

    public function getTarifa(Request $request){

        $tarifa = TransporteTarifa::where('id',$request->tarifaId)->get()->first();
        return response()->json([
            'message'=>'Tarifa obtenida exitosamente',
            'tarifa'=>$tarifa,
        ]);
    }



   


}
