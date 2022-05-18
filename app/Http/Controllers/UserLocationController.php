<?php

namespace App\Http\Controllers;

use App\UserLineaTransporte;
use App\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLocationController extends Controller
{
    public static function updateLocation(Request $request){
            
            UserLocation::updateOrCreate(
                [
                    'user_id'=>Auth::user()->id,
                ],
                [
                'latitud'=> $request->latitud,
                'longitud'=> $request->longitud,
                'driving'=>$request->driving,
                'updated_at'=>now(),
                ]
            );

            return response()->json([
                'message'=>'Ubicación Actualizada',
                'user_id'=>Auth::user()->id,
                'location'=>[
                    'latitude'=>$request->latitud,
                    'longitude'=>$request->longitud,
                    'driving'=>$request->driving,
                ]
                ],200);
    }

    public static function turnOffLocation(){
        $users = UserLocation::where('driving',true)->where('updated_at','<',now()->subMinutes(2))->get();
        foreach ($users as $user) {
            $user->driving = 'false';
            $user->save();
        }    
    }


    public static function getLocation(Request $request){
        UserLocationController::turnOffLocation(); //Si hay conductores que no han renovado su ubicación en 30 min
        if ($request->lineaTransporte_id) {
            
            $data = [];

            $choferes = UserLineaTransporte::whereHas('users', function ($query) {
                $query->where('type_user', 2)->whereHas('location',function($location){
                    $location->where('driving',true);
                });
            })
            ->with('users')
            ->get();
            
            foreach ($choferes as $key => $chofer) {
                
                foreach ($chofer->users as $key => $user) {
                    $location = [
                        'user_name' => $user->full_name,
                        'user_id' => $user->id,
                        'location' => [
                            'latitude' => $user->location->latitud,
                            'longitude' => $user->location->longitud,
                            'driving'=> $user->location->driving
                        ],
                        'line' => $chofer->linea_nombre,
                        'line_id' => $chofer->id // Este es el id de la linea
                    ];
                    array_push($data, $location);
                }

              
            }

            return response()->json([
                'message' => 'Localización obtenida satisfactoriamente',
                'drivers' => $data,
            ], 200);
        }




      


    }
}
