<?php

namespace App\Http\Controllers\panel;

use App\User;
use App\DatosChofer;
use App\SolicitudChofer;
use App\UserLineaTransporte;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChoferesController extends Controller
{
 
    public function index()
    {
        return view('panel.choferes.index');
    }

    public function list(){
        return view('panel.choferes.list');
    }
    
    public function getChoferesSolicitudes()
    {
        $choferes = SolicitudChofer::with('user:id,full_name,email')
        ->orderBy('estado_solicitud', 'desc')
        ->orderBy('created_at','desc')
        ->paginate(40);

        return response()->json($choferes);
    }

    public function createSolcitudChofer(Request $request)
    {

        if (Auth::user()->type_user != '2') {
             
            $solicitud = SolicitudChofer::updateOrCreate(
                [
                    'user_id'=>Auth::user()->id,
                    'estado_solicitud'=>'PENDIENTE',
                ],
                [
                'name_user' => Auth::user()->full_name,
                'user_id' => Auth::user()->id,
                'placa'=> $request->placa,
                'dueño_vehiculo'=> $request->dueño_vehiculo,
                'marca_vehiculo'=> $request->marca_vehiculo,
                'año_vehiculo'=> $request->año_vehiculo,
                'tipo_combustible'=> $request->tipo_combustible,
                'estado_solicitud'=>'PENDIENTE',
                ]
            );

            return response()->json($solicitud);
        }else{
            return response()->json(['message'=>'El usuario ya es un chofer']);
        }

    
    }

    public function aprobarSolicitudChofer(Request $request)
    {
        $solicitud = SolicitudChofer::find($request->id);
        $solicitud->estado_solicitud = 'APROVADA';
        $solicitud->save();
        $solicitud->user->type_user = 2;
        $solicitud->user->save();
    
        $datosChofer = new DatosChofer();
        $datosChofer->name_user = $solicitud->name_user;
        $datosChofer->user_id = $solicitud->user_id;
        $datosChofer->placa = $solicitud->placa;
        $datosChofer->dueño_vehiculo = $solicitud->dueño_vehiculo;
        $datosChofer->año_vehiculo = $solicitud->año_vehiculo;
        $datosChofer->marca_vehiculo = $solicitud->marca_vehiculo;
        $datosChofer->tipo_combustible = $solicitud->tipo_combustible;
        $datosChofer->save();

        return response()->json($solicitud);
    }
   

    public function deleteSolicitudChofer(Request $request)
    {
        $solicitud = SolicitudChofer::find($request->id);
        $solicitud->delete();
        return response()->json($solicitud);
    }

  
    public function deleteDatosChofer(Request $request)
    {
        $datosChofer = DatosChofer::find($request->id);
        $solicitud = SolicitudChofer::where('user_id',$datosChofer->user_id)->first();
        if($solicitud){
            $solicitud->delete();
        }
        $datosChofer->user->type_user = 1;
        $datosChofer->user->save();
        $datosChofer->delete();
        return response()->json($datosChofer);
    }

 
    public function searchSolicitudes(Request $request)
    {
        $solicitud = SolicitudChofer::with('user:id,full_name,email')
        ->where('name_user','like','%'.$request->search.'%')
        ->orWhere('placa','like','%'.$request->search.'%')
        ->orWhere('dueño_vehiculo','like','%'.$request->search.'%')
        ->orderBy('estado_solicitud', 'desc')
        ->orderBy('created_at','desc')
        ->paginate(20);

        return response()->json($solicitud);
    }

    
    public function getSolicitudChofer(Request $request)
    {
        $solicitud = SolicitudChofer::with('user:id,full_name,email')
        ->where('id',$request->id)
        ->first();

        return response()->json($solicitud);
    }

  
    public function getChoferes()
    {
        $choferes = DatosChofer::with('user:id,full_name,email')
        ->orderBy('created_at','desc')
        ->paginate(40);

        return response()->json($choferes);
    }

    
    public function searchChofer(Request $request)
    {
        $chofer = DatosChofer::with('user:id,full_name,email')
        ->where('name_user','like','%'.$request->search.'%')
        ->orWhere('placa','like','%'.$request->search.'%')
        ->orWhere('dueño_vehiculo','like','%'.$request->search.'%')
        ->orderBy('created_at','desc')
        ->paginate(20);

        return response()->json($chofer);
    }


    public function getDatosChoferes(Request $request){
  
        
        $datos = DatosChofer::with('user:id,full_name,email')
        ->where('user_id',$request->id)
        ->first();

        if (User::where('id',$request->id)->with('lineaTransporte')->get()->first()->lineaTransporte != null) {
            $datos->lineaTransporte = User::where('id',$request->id)->with('lineaTransporte')->get()->first()->lineaTransporte->linea_nombre;
            
        }else{
            $datos->lineaTransporte = 'Sin linea de transporte';
        }
        $lineaAutbuses = UserLineaTransporte::all();
        return response()->json([    
            'datos'=>$datos,
            'lineasAutobuses' => $lineaAutbuses,
        ]);
    }

   
    public function changeLinea(Request $request){
        $user = User::find($request->id);
        $user->lineaTransporte_id = $request->lineaTransporte_id;
        $user->save();
        return response()->json($user);
    }


}