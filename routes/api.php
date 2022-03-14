<?php

use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/***************************/
/******** USERS ************/
/***************************/

Route::group(['prefix' => 'auth'],function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signUp');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'AuthController@logout');
        Route::post('user', 'AuthController@user');
        Route::get('createQr','WalletController@exampleqr');
        Route::post('updateLocation','UserlocationController@updateLocation');
        Route::post('getLocation','UserLocationController@getLocation');

    });
});

/***************************/
/******** WALLET ************/
/***************************/

Route::group(['prefix'=>'walltet','middleware' => 'auth:api'],function () {
    Route::post('getQr','WalletController@getQr');
    Route::post('generateQr','WalletController@generateQr');
    Route::post('pay','WalletController@cobrar');
    Route::post('recharge', 'WalletController@recargar');
    Route::post('transactions', 'WalletController@transactions');
    Route::post('refund','WalletController@refund');

});

/***************************/
/******** BUSLINES ************/
/***************************/
Route::group(['prefix'=>'buslines','middleware' => 'auth:api'],function () {
     Route::post('/','UserLineaTransporteController@getBusLines');
     Route::post('/getBusCollection','UserLineaTransporteController@getBusCollection');
});