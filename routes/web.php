<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

// Terminos y Condiciones
Route::get('/terminos', function () {
    return view('terminos');
})->name('terminos');

Route::get('/policy', function () {
    return view('policy');
})->name('policy');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index');

    Route::group(['middleware' => 'CheckRol'], function () {
        Route::get('/choferes', 'panel\ChoferesController@index');
        Route::post('/getsolicitudeschoferes', 'panel\ChoferesController@getChoferesSolicitudes');
        Route::post('/solicitudchofer/create', 'panel\ChoferesController@createSolcitudChofer');
        Route::post('/getsolicitudData', 'panel\ChoferesController@getSolicitudChofer');
        Route::post('/aceptarsolicitud', 'panel\ChoferesController@aprobarSolicitudChofer');
        Route::post('/searchsolicitudes', 'panel\ChoferesController@searchSolicitudes');
        Route::post('/deleteSolicitud', 'panel\ChoferesController@deleteSolicitudChofer');


        Route::get('/choferes/list', 'panel\ChoferesController@list');
        Route::post('/choferes/get', 'panel\ChoferesController@getChoferes');
        Route::post('/getDatosChoferes', 'panel\ChoferesController@getDatosChoferes');
        Route::post('/choferes/changeLinea', 'panel\ChoferesController@changeLinea');
        Route::post('/choferes/searchChofer', 'panel\ChoferesController@searchChofer');


        Route::get('/lineaTransporte', 'panel\LineaTransporteController@index');
        Route::post('/lineaTransporte/getlineasTransporte', 'panel\LineaTransporteController@getlineasTransporte');
        Route::post('/lineaTransporte/guardarLinea', 'panel\LineaTransporteController@guardarLinea');


        Route::get('/recargas/index', 'panel\RecargasController@index');
        Route::post('/recargas/chequearReferencia', 'panel\RecargasController@chequearReferencia');
        Route::post('/recargas/subirReferencias', 'panel\RecargasController@subirReferencias');

        Route::get('/liquidaciones/index', 'panel\LiquidacionController@index');
        Route::post('/liquidaciones/getLiquidaciones', 'panel\LiquidacionController@getLiquidaciones');
        Route::post('/liquidaciones/updateComision', 'panel\LiquidacionController@updateComision');
    });
    Route::get('/miperfil', 'panel\PerfilController@index')->middleware('isChofer');
    Route::post('/miperfil/actualizarDatos', 'panel\PerfilController@actualizarDatosPerfil')->middleware('isChofer');
});
