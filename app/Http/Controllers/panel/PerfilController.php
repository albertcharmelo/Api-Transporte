<?php

namespace App\Http\Controllers\panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserDatosBancarios;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    function index(){
        return view('panel.perfil.index');
    }

    function actualizarDatosPerfil(Request $request){
        //update user data
        $user = User::where('id',Auth::user()->id)->first()->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'gender'=>$request->gender,
        ]);

        $datosBancarios = UserDatosBancarios::updateOrCreate(
            [
                'user_id'=>Auth::user()->id,

            ],
            
            [
            'numero_de_cuenta'=> $request->numero_de_cuenta,
            'id_card'=> $request->id_card,
            'tipo_cuenta'=> $request->tipo_cuenta,
            'banco'=> $request->banco,
        ]);

        return redirect()->back()->with('success','Datos actualizados correctamente');

    }
}
