<?php

namespace App\Http\Controllers;

use App\Events\CreditTransaction;
use App\User;
use App\QrCodeUser;
use App\UserTransaction;
use App\UserWallet;
use Google\Service\ShoppingContent\Amount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WalletController extends Controller
{
    public function getQr(Request $request){

        try {
           $qr = QrCodeUser::where('users_id',Auth::user()->id)->get()->first();
            return response()->json([
                'qr'=>$qr->qr_image,
            ],200);

        } catch (\Throwable $th) {
            return response()->json([
                'message'=>"Qr no encontrado!"
            ],401);
        }
    }


    public function generateQr(Request $request){
        try {
           //Eliminamos el Qr existente 
           $qr = QrCodeUser::where('users_id',Auth::user()->id)->get()->first();
           Storage::disk('qr')->delete($qr->qr_name .".png");
           $qr->delete();
           Storage::disk('s3')->delete('path/file.jpg');
           //Creamos un nuevo Qr
           $qr_name = hash('sha256',now(),false);
           $qr_registered = QrCodeUser::create([
               'qr_image'=>"qr_api_transporte/$qr_name.png",
               'users_id'=>Auth::user()->id,
               'qr_name'=>$qr_name,
            ]);

            $qr_idshow = QrCodeUser::where('id',$qr_registered->id)->get()->first();
            $newQr = QrCode::format('png')->size(250)->color(40, 209, 123)->generate($qr_idshow->qr_idShow);
            Storage::disk('qr')->put("$qr_name".".png",$newQr); 

            return response()->json([
                'message'=>"Nuevo Qr creado satisfactoriamente",
                'qr'=>$qr_registered->qr_image,
            ],200);


        } catch (\Throwable $th) {
            return response()->json([
                'message'=>"Qr no encontrado!"
            ],401);
        }
    }


    static public function cobrar(Request $request){
        
        
        
        $data_from_qr =QrCodeUser::where('qr_idShow',$request->qr_idShow)
                                ->with('user')
                                ->get()
                                ->first();
        
        $client_wallet = UserWallet::where('user_id',$data_from_qr->user->id)
                                ->get()
                                ->first();

        $driver_wallet = UserWallet::where('user_id',Auth::user()->id)
                                    ->get()
                                    ->first();
        $amount = floatval($request->amount);
     try {
        if ($client_wallet->creditos >= $amount) {
            $client_wallet->creditos = floatval($client_wallet->creditos) - $amount;
            $driver_wallet->creditos = floatval($driver_wallet->creditos) + $amount;
            $client_wallet->save();
            $driver_wallet->save();
            $transaccion = event(new CreditTransaction($client_wallet->user_id,$driver_wallet->user_id,$amount,$request->transaction));
            return response()->json([
                'message'=> 'Transacción Realizada',
                'newDriverBalance'=>$client_wallet->creditos,
                'newClientBalance'=> $client_wallet->creditos,

            ],200);
        }else{
            return response()->json([
                'message'=> 'El cliente no posee el saldo suficiente'
            ],200);
        }
     } catch (\Throwable $th) {
         return response()->json([
             'message'=> 'Error en al efectuar la transaccion',

         ],400);
     }
    }


    public static function recargar (Request $request){
        $user = Auth::user();
        
        if($user && $request->date && $request->reference   && $request->bank){
            
            
            $user->wallet->creditos =  $user->wallet->creditos + 10;
            $user->wallet->save();
            return response()->json([
                'message' => 'Recarga de creditos realizada',
                'userBalance'=>$user->wallet->creditos
            ], 200);
        }

    }
    
    public function transactions(Request $request){
        $user = Auth::user();
        
        $transaccion_unfilter = UserTransaction::where('client_id',$user->id)
                                                ->orWhere('driver_id',$user->id)->get();
        
        if ($user->type_user == '1') {
            return response()->json([
                'message' => 'Transacciones enviadas',
                'user_id'=>Auth::user()->id,
                'transactions'=>$transaccion_unfilter
            ],200);
        }elseif($user->type_user == '2'){
            $transaccion_filtered = [];
            foreach ($transaccion_unfilter as $key => $transaccion) {
                if ($transaccion->driver_id == Auth::user()->id) {
                    switch ($transaccion->transaction) {
                        case 'DISCOUNT':
                            $transaccion->transaction = 'ADD';
                            
                            array_push($transaccion_filtered,$transaccion);
                            break; 
                        default:
                            array_push($transaccion_filtered,$transaccion);
                            break;
                    }
                }else {
                    array_push($transaccion_filtered,$transaccion);
                }
            }

            return response()->json([
                'message' => 'Transacciones enviadas',
                'user_id'=>Auth::user()->id,
                'transactions'=>$transaccion_filtered
            ],200);
        }
        

    }
    

}