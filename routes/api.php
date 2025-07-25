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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signUp');
    Route::post('password/email', 'AuthController@forgotPassword2');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'AuthController@logout');
        Route::post('user', 'AuthController@user');
        Route::get('createQr', 'WalletController@exampleqr');
        Route::post('updateLocation', 'UserLocationController@updateLocation');
        Route::post('getLocation', 'UserLocationController@getLocation');
        Route::post('createSolicitudChofer', 'panel\ChoferesController@createSolcitudChofer');
        Route::get('delete', 'AuthController@deleteAccount');
    });
});

/***************************/
/******** WALLET ************/
/***************************/

Route::group(['prefix' => 'walltet', 'middleware' => 'auth:api'], function () {
    Route::post('getQr', 'WalletController@getQr');
    Route::post('generateQr', 'WalletController@generateQr');
    Route::post('pay', 'WalletController@cobrar');
    Route::post('recharge', 'WalletController@recargar');
    Route::post('transactions', 'WalletController@transactions');
    Route::post('refund', 'WalletController@refund');
    Route::post('liquidacion', 'WalletController@liquidacion');
});

Route::group(['prefix' => 'bank'], function () {
    Route::post('consultar', 'PaymentBankController@consultar');
    Route::post('historial', 'PaymentBankController@historial');
    Route::post('banklist', 'PaymentBankController@banks');
    Route::post('payp2p', 'PaymentBankController@sendPay');
    Route::post('payp2pconfirm', 'PaymentBankController@ValidateP2P');
});

/***************************/
/******** BUSLINES ************/
/***************************/
Route::group(['prefix' => 'buslines', 'middleware' => 'auth:api'], function () {
    Route::post('/', 'UserLineaTransporteController@getBusLines');
    Route::post('/getBusCollection', 'UserLineaTransporteController@getBusCollection');
    Route::post('/getTarifas', 'UserLineaTransporteController@getTarifas');
    Route::post('/getTarifa', 'UserLineaTransporteController@getTarifa');
    Route::post('/createDatosChofer', 'panel\ChoferesController@createDatosChofer');
});

/***************************/
/******** NOTIFICATION ************/
/***************************/
Route::group(['prefix' => 'notification', 'middleware' => 'auth:api'], function () {
    Route::post('updateTokenNotification', 'AuthController@updateTokenNotification');
    Route::post('getTokenNotification', 'AuthController@getTokenNotification');
});
