<?php

namespace App\Http\Controllers\panel;

use App\Recarga;
use Illuminate\Http\Request;
use App\Imports\RecargasImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class RecargasController extends Controller
{
    public function index(){
        return view('panel.recargas.index');
    }

    public function subirReferencias(Request $request){
        $file = $request->file('archivo');
        $import = new RecargasImport();
        $models = Excel::import($import, $file);
        return back()->with('success', 'Importación exitosa');
    }


    public function chequearReferencia(Request $request){
        $referencia = $request->input('referencia');
        $recarga = Recarga::where('referencia',$referencia)->first();
        if($recarga){
            return response()->json(['status'=>'success','message'=>'La referencia ya existe']);
        }else{
            return response()->json(['status'=>'success','message'=>'La referencia no existe']);
        }
    }





    
}
