<?php

namespace App\Http\Controllers;

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
        $buslines = UserLineaTransporte::where('id',$request->busLineId)->get()->first();
        
        return response()->json([
            'message'=>'Buses obtenidos exitosamente',
            'buslines'=>$buslines,
        ]);
    }


}
