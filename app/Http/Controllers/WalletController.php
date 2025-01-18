<?php

namespace App\Http\Controllers;

use App\User;
use App\QrCodeUser;
use App\UserWallet;
use App\Liquidacion;
use App\UserTransaction;
use Illuminate\Http\Request;
use App\Events\CreditTransaction;
use App\Events\RecargaUserWallet;
use App\Events\RegisterAppLog;
use App\Http\Requests\ValidateP2PRequest;
use App\UserRecarga;
use Carbon\Carbon;
use Faker\Provider\ar_SA\Payment;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Google\Service\ShoppingContent\Amount;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WalletController extends Controller
{
    public function getQr(Request $request)
    {

        try {
            $qr = QrCodeUser::where('users_id', Auth::user()->id)->get()->first();
            return response()->json([
                'qr' => $qr->qr_image,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Qr no encontrado!"
            ], 401);
        }
    }


    public function generateQr(Request $request)
    {
        try {
            //Eliminamos el Qr existente 
            $qr = QrCodeUser::where('users_id', Auth::user()->id)->get()->first();
            if ($qr) {
                Storage::disk('qr')->delete($qr->qr_name . ".png");
                $qr->delete();
            }

            //Creamos un nuevo Qr
            $qr_name = hash('sha256', now(), false);
            $qr_registered = QrCodeUser::create([
                'qr_image' => "qr/$qr_name.png",
                'users_id' => Auth::user()->id,
                'qr_name' => $qr_name,
            ]);

            $qr_idshow = QrCodeUser::where('id', $qr_registered->id)->get()->first();
            $newQr = QrCode::format('png')->size(250)->color(40, 209, 123)->generate($qr_idshow->qr_idShow);
            Storage::disk('qr')->put("$qr_name" . ".png", $newQr);

            return response()->json([
                'message' => "Nuevo Qr creado satisfactoriamente",
                'qr' => $qr_registered->qr_image,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Qr no encontrado!"
            ], 401);
        }
    }


    static public function cobrar(Request $request)
    {



        $data_from_qr = QrCodeUser::where('qr_idShow', $request->qr_idShow)
            ->with('user')
            ->get()
            ->first();

        $client_wallet = UserWallet::where('user_id', $data_from_qr->user->id)
            ->get()
            ->first();


        $driver_wallet = UserWallet::where('user_id', Auth::user()->id)
            ->get()
            ->first();


        $amount = floatval($request->amount);
        try {
            if ($client_wallet->creditos >= $amount) {
                $client_wallet->creditos = floatval($client_wallet->creditos) - $amount;
                $driver_wallet->creditos = floatval($driver_wallet->creditos) + $amount;
                $client_wallet->save();
                $driver_wallet->save();
                $transaccion = event(new CreditTransaction($client_wallet->user_id, $driver_wallet->user_id, $amount, $request->transaction, $request->tickets_amount));
                return response()->json([
                    'message' => 'Transacción Realizada',
                    'newDriverBalance' => $driver_wallet->creditos,
                    'newClientBalance' => $client_wallet->creditos,
                    'tickets_amount' => $request->tickets_amount,
                    'transaccion' => $transaccion,

                ], 200);
            } else {
                return response()->json([
                    'message' => 'El cliente no posee el saldo suficiente'
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error en al efectuar la transaccion',
                'error' => $th->getMessage(),
            ], 401);
        }
    }

    public static function refund(Request $request)
    {
        $transaccion = UserTransaction::where('id', $request->id)->whereBetween('created_at', [now()->subHour(24), now()])->get()->first();
        if ($transaccion &&  $transaccion->transaction != 'RETURN') {
            $transaccion->transaction = 'RETURN';
            $transaccion->save();
            $driver = User::find($transaccion->driver_id);
            $cliente = User::find($transaccion->client_id);

            $driver->wallet->creditos  =  $driver->wallet->creditos - $transaccion->amount;
            $driver->wallet->save();

            $cliente->wallet->creditos  =  $cliente->wallet->creditos + $transaccion->amount;
            $cliente->wallet->save();


            return response()->json([
                'message' => 'Reembolso Exitoso',
                'Driver_wallet' => $driver->wallet->creditos,
                'Client_wallet' => $cliente->wallet->creditos,
                'trasaccion_reembolso' => $transaccion,
            ], 200);
        } else {
            return response()->json([
                'message' => 'El tiempo de reembolso ha exedido los 24hrs',
            ], 400);
        }
    }

    public static function recargar(ValidateP2PRequest $request)
    {
        $user = Auth::user();

        if ($user) {
            if (WalletController::validateIfExistReference($request->Reference)) {
                return response()->json([
                    'message' => 'El pago ha sido registrado anteriormente',
                ], 400);
            }

            DB::beginTransaction();
            try {
                $response_from_valdiate  =  PaymentBankController::ValidateP2P($request);

                if ($response_from_valdiate->getStatusCode() == 200) {
                    ## Disparar el evento para procesar la recarga
                    $evento =  event(new RecargaUserWallet($user->id, $request->Amount, $request->BankCode, $request->Reference));
                    DB::commit();
                    return response()->json([
                        'message' => 'Recarga realizada con exito',
                        'userBalance' => $user->wallet->creditos
                    ], 200);
                } else {

                    DB::rollBack();
                    DB::transaction(function () use ($request, $user, $response_from_valdiate) {
                        event(new RegisterAppLog(
                            'recargas',
                            "Error en " . __CLASS__ . "::" . __FUNCTION__ . " - " .
                                $response_from_valdiate->content()  ?? 'Null',
                            400,
                            $request->Reference,
                            $request->Amount,
                            $request->BankCode,
                            $request->PhoneNumber,
                            $user->id,
                            0
                        ));
                    });

                    return response()->json([
                        'message' => 'Error al validar la transaccion',
                        'error' => $response_from_valdiate->content(),
                    ], 400);
                }
            } catch (\Throwable $th) {
                dd('1');

                DB::rollBack();

                DB::transaction(function () use ($request, $user, $th) {
                    event(new RegisterAppLog(
                        'recargas',
                        "Error en " . __CLASS__ . "::" . __FUNCTION__ . " - " . $th->getMessage() ?? 'Null',
                        400,
                        $request->Reference,
                        $request->Amount,
                        $request->BankCode,
                        $request->PhoneNumber,
                        $user->id,
                        0
                    ));
                });

                return response()->json([
                    'message' => 'Error al validar la transaccion en el servidor, contacte a soporte',
                    'error' => $th->getMessage(),
                ], 400);
            }
        } else {

            event(new RegisterAppLog(
                'recargas',
                "Error en " . __CLASS__ . "::" . __FUNCTION__ . " - " . 'Usuario no encontrado',
                400,
                $request->Reference,
                $request->Amount,
                $request->BankCode,
                $request->PhoneNumber,
                0,
                0
            ));
            return response()->json([
                'message' => 'Error al validar la transaccion en el servidor, contacte a soporte',
            ]);
        }
    }

    public function transactions(Request $request)
    {

        $user = Auth::user();

        $transaccion_unfilter = UserTransaction::where('client_id', $user->id)
            ->orWhere('driver_id', $user->id)
            ->orderBy('updated_at', 'DESC')
            ->paginate(10);


        $user_type = Auth::user()->type_user == 2 ? 'CONDUCTOR' : 'CLIENTE';

        return response()->json([
            'message' => 'Transacciones enviadas',
            'user_id' => Auth::user()->id,
            'user_type' => $user_type,
            'transactions' => $transaccion_unfilter
        ], 200);
    }

    public function liquidacion(Request $request)
    {
        $user = Auth::user();

        if ($user->type_user == 2) {
            if ($user->datos_bancarios) {
                if ($request->monto_liquidar > $user->wallet->creditos) {
                    return response()->json([
                        'message' => 'El monto a liquidar es mayor al saldo de la cuenta',

                    ], 200);
                } else {
                    if ($request->monto_liquidar <= 0) {
                        return response()->json([
                            'message' => 'El monto a liquidar es menor o igual a cero',

                        ], 200);
                    } else {
                        $find_liquidacion = Liquidacion::where('user_id', $user->id)->get()->first();
                        if ($find_liquidacion) {
                            $find_liquidacion->monto_liquidar = $request->monto_liquidar + $find_liquidacion->monto_liquidar;
                            $find_liquidacion->save();
                            $user->wallet->creditos = $user->wallet->creditos - $request->monto_liquidar;
                            $user->wallet->save();
                            return response()->json([
                                'message' => 'Liquidacion realizada',
                                'liquidacion' => $find_liquidacion,
                                'userBalance' => $user->wallet->creditos
                            ], 200);
                        } else {

                            $liquidacion = Liquidacion::create([
                                'user_id' => Auth::user()->id,
                                'banco' => Auth::user()->datos_bancarios->banco,
                                'cedula' => Auth::user()->datos_bancarios->id_card,
                                'numero_de_cuenta' => Auth::user()->datos_bancarios->numero_de_cuenta,
                                'monto_liquidar' => $request->monto_liquidar,
                                'tipo_cuenta' => Auth::user()->datos_bancarios->tipo_cuenta,
                            ]);
                            $user->wallet->creditos = $user->wallet->creditos - $request->monto_liquidar;
                            $user->wallet->save();
                            return response()->json([
                                'message' => 'Liquidacion realizada',
                                'liquidacion' => $liquidacion,
                                'userBalance' => $user->wallet->creditos
                            ], 200);
                        }
                    }
                }
            } else {
                return response()->json([
                    'message' => 'Debe completar los datos del perfil en siguiente enlace: ',
                    'link' => URL::to('miperfil')
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'No posee los permisos para realizar esta accion',
            ], 200);
        }
    }


    public static function validateIfExistReference($reference): bool
    {
        $recarga = UserRecarga::where('referencia', $reference)->get()->first();
        if ($recarga) {
            return true;
        } else {
            return false;
        }
    }
}
