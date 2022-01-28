<?php

namespace App\Http\Controllers;

use App\User;
use App\QrCodeUser;
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

        try {
            if ($client_wallet->creditos >= $request->amount) {
                $client_wallet->creditos = $client_wallet->creditos - $request->amount;
                $driver_wallet->creditos = $driver_wallet->creditos + $request->amount;

                $client_wallet->save();
                $driver_wallet->save();



            }else{
                return response()->json([
                    'message'=> 'El cliente no posee el saldo suficiente'
                ],200);
            }



        } catch (\Throwable $th) {
            //throw $th;
        }                                    


    }
    
    

}
