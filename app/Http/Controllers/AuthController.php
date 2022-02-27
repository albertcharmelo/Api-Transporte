<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\QrCodeUser;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUser;
use App\UserWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function signUp(StoreUser $request)
    {   
        
        //Create user
        try {
            $request->validated();
            $user = User::create([
                'full_name' => $request->name,
                'id_card'=>$request->id_card,
                'type_id_card'=>$request->type_id_card,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            UserWallet::create([
                'user_id'=>$user->id,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al crear el usuario',
                'error'=>$th,
            ], 400);
        }
        
     
        //Create first Qr code of user
        try {
            $usuarioRegistrado = User::find($user->id);
            $qr_name = hash('sha256',now(),false);
            $qr_registered = QrCodeUser::create([
                'qr_image'=>"qr_api_transporte/$qr_name.png",
                'users_id'=>$usuarioRegistrado->id,
                'qr_name'=>$qr_name,
            ]);
            $qr_idshow = QrCodeUser::where('id',$qr_registered->id)->get()->first();
            $qr = QrCode::format('png')->size(250)->color(40, 209, 123)->generate($qr_idshow->qr_idShow);
            Storage::disk('qr')->put("$qr_name".".png",$qr); 
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al crear el qr del usuario',
                'error'=>$th,
            ], 400);
        }
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return response()->json([
            'message' => 'Usuario creado satisfactoriamente',
            'access_token' => $tokenResult->accessToken,
        ], 201);
    }
    
    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'message' => 'Inicio de sesión satisfactorio',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ],200);
    }

    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Cierre de Sesion Satisfactoriamente'
        ]);
    }

    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        $usuario = User::where('id',Auth::user()->id)
        ->with('qrCode')
        ->with('type_user')
        ->with('wallet')
        ->get();
        return response()->json([
            'user'=>$usuario
        ],200);
    }
}