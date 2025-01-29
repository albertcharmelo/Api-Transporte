<?php

namespace App\Http\Controllers;

use App\BankUrisApi;
use App\BncToken;
use App\Events\RegisterAppLog;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendP2PRequest;
use App\Http\Requests\ValidateP2PRequest;
use App\Liquidacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentBankController extends Controller
{


    public static function getToken(): string
    {
        $token =  BncToken::where('expiration_date', '>', now())->first();
        if ($token) {
            return $token->token;
        } else {
            PaymentBankController::authenticate();
            $newToken =  BncToken::where('expiration_date', '>', now())->first();
            return $newToken->token;
        }
    }

    public static
    function refere()
    {
        //20220831090831
        $fecha = date('Y-m-d h:i:s', time());
        $fecha = strval($fecha);
        $fecha = str_replace("-", "", $fecha);
        $fecha = str_replace(":", "", $fecha);
        $fecha = str_replace(" ", "", $fecha);
        $result = $fecha;
        return $result;
    }

    public static function createHash($data)
    {
        $validation = hash('sha256', utf8_encode($data));
        return $validation;
    }

    public static function gPost($gurl, $jsonSolicitud)
    {
        $ch = curl_init($gurl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonSolicitud);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        ## Usamos dd para debuggear
        // dd($result);
        return $result;
    }

    public static function encrypt($data, $Masterkey)
    {
        $method = 'aes-256-cbc';
        $sSalt = chr(0x49) . chr(0x76) . chr(0x61) . chr(0x6e) . chr(0x20) . chr(0x4d) . chr(0x65) . chr(0x64) . chr(0x76) . chr(0x65) . chr(0x64) . chr(0x65) . chr(0x76);

        $pbkdf2 = hash_pbkdf2('SHA1', $Masterkey, $sSalt, 1000, 48, true);
        $key = substr($pbkdf2, 0, 32);
        $iv =  substr($pbkdf2, 32, strlen($pbkdf2));


        $string =  mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');
        $encrypted = base64_encode(openssl_encrypt($string, $method, $key, OPENSSL_RAW_DATA, $iv));
        return $encrypted; //tools
    }


    public static function decrypt($data, $Masterkey)
    {
        $method = 'aes-256-cbc';
        $sSalt = chr(0x49) . chr(0x76) . chr(0x61) . chr(0x6e) . chr(0x20) . chr(0x4d) . chr(0x65) . chr(0x64) . chr(0x76) . chr(0x65) . chr(0x64) . chr(0x65) . chr(0x76);

        $pbkdf2 = hash_pbkdf2('SHA1', $Masterkey, $sSalt, 1000, 48, true);
        $key = substr($pbkdf2, 0, 32);
        $iv =  substr($pbkdf2, 32, strlen($pbkdf2));

        $string = openssl_decrypt(base64_decode($data), $method, $key, OPENSSL_RAW_DATA, $iv);
        $decrypted = mb_convert_encoding($string, 'UTF-8', 'UTF-16LE');

        return $decrypted;
    }

    /**
     * Authenticates with the BNC service.
     *
     * This function performs user authentication with the BNC service using the provided environment variables.
     * It builds a request object, encrypts data, validates the request, sends a POST request to the BNC API, 
     * and returns the decoded JSON response.
     *
     * The expected response format is a JSON object with the following properties:
     *
     * - status (string): The status of the authentication request. "OK" indicates success.
     * - message (string): A message describing the result of the authentication.
     * - value (string): An encrypted value returned by the BNC service.
     * - validation (string): A validation hash for the request.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the BNC service response.
     * @throws \Exception If any errors occur during the authentication process.
     */
    public static function authenticate(): JsonResponse
    {
        $masterKey = env('BNC_SECRET_KEY');
        $clientGUID = env('BNC_CLIENT_GUID');

        $clientValue = '{"ClientGUID":"' . $clientGUID . '"}';
        $value = self::encrypt($clientValue, $masterKey);

        ## Validation
        $validation = self::createHash($clientValue);

        ## Request
        $req = array("ClientGUID" => $clientGUID, "value" => $value, "Validation" => $validation, "Reference" => '', "swTestOperation" => false);
        $jsonReq = json_encode($req);

        ## Send Post Req
        $gurl = BankUrisApi::URI_AUTH;
        $gResult = json_decode(self::gPost($gurl, $jsonReq), true);

        ## Se guarda en la DB para futuras consultas
        $token = BncToken::create([
            'token' => $gResult['value'],
            'expiration_date' => now()->addDay(),
        ]);


        return response()->json($token, 200);
    }


    /**
     * Consulta de movimientos.
     *
     * Esta función consulta los movimientos de una cuenta específica utilizando el servicio BNC.
     * Valor Devuelto: Diccionario con las siguientes propiedades:
     * - Key: Número de cuenta
     * - Date: fecha del movimiento en formato dd/MM/yyyy.
     * - Value: Lista de movimientos de los 3 días más recientes:
     *   - Date: fecha del movimiento en formato dd/MM/yyyy.
     *   - ControlNumber: string, número de control de la transacción.
     *   - Amount: decimal, monto de la transacción.
     *   - Code: string, código de operación.
     *   - Type: string, tipo de movimiento.
     *   - BalanceDelta: string, indica si es un ingreso o egreso.
     *   - ReferenceA: string, referencia 1.
     *   - ReferenceB: string, referencia 2. Este campo puede no tener valor, depende del tipo de movimiento.
     *   - ReferenceC: string, referencia 3. Este campo puede no tener valor, depende del tipo de movimiento.
     *   - ReferenceD: string, referencia 4. Este campo puede no tener valor, depende del tipo de movimiento.
     *   
     * @return \Illuminate\Http\JsonResponse A JSON response containing the BNC service response.
     * @throws \Exception If any errors occur during the authentication process.
     **/
    public static function historial(): JsonResponse
    {
        $token = self::getToken();
        if (!$token) {
            return response()->json(['error' => 'Token not found'], 404);
        }
        $clientGUID = env('BNC_CLIENT_GUID');
        $masterKey = env('BNC_SECRET_KEY');


        $tokenDecrypted = self::decrypt($token, $masterKey);
        $workingKey = json_decode($tokenDecrypted, true)['WorkingKey'];

        ## Data
        $data = array(
            'AccountNumber' => env('Asad_Bnc_Account_Number'),
            'ClientID' => env('Asad_Bnc_ClientID'),
        );

        $dataJson = json_encode($data);

        ## Encriptado
        $value = self::encrypt($dataJson, $workingKey);

        ## Validation
        $validation = self::createHash($dataJson);

        ## Req
        $req = array("ClientGUID" => $clientGUID, "value" => $value, "Validation" => $validation, "Reference" => '', "swTestOperation" => false);
        $jsonReq = json_encode($req);
        ## Send Post Req
        $gurl = BankUrisApi::URI_HISTORY;
        $gResult = json_decode(self::gPost($gurl, $jsonReq), true);
        return response()->json($gResult, 200);
    }


    /**
     * Validate a P2P (peer-to-peer) payment request.
     *
     * @param ValidateP2PRequest $request The request object containing the P2P payment details.
     * @return JsonResponse The JSON response containing the result of the validation.
     *
     * Valor Devuelto:
     * - Amount: Decimal, monto de la transacción.
     * - BalanceDelta: String, indica si es un ingreso o egreso.
     * - Code: String, código de operación.
     * - ControlNumber: String, número de control de la transacción.
     * - Date: fecha del movimiento en formato dd/MM/yyyy.
     * - MovementExists: Bool, este campo indica si existe o no el movimiento.
     * - ReferenceA: String, referencia 1.
     * - ReferenceB: String, referencia 2. Este campo puede no tener valor, depende del tipo de movimiento.
     * - ReferenceC: String, referencia 3. Este campo puede no tener valor, depende del tipo de movimiento.
     * - ReferenceD: String, referencia 4. Este campo puede no tener valor, depende del tipo de movimiento.
     * - Type: String, tipo de movimiento.
     */
    public static function ValidateP2P(ValidateP2PRequest $request): JsonResponse
    {
        $token = self::getToken();

        if (!$token) {
            return response()->json(['error' => 'Token not found'], 404);
        }
        $clientGUID = env('BNC_CLIENT_GUID');
        $masterKey = env('BNC_SECRET_KEY');


        $tokenDecrypted = self::decrypt($token, $masterKey);
        $workingKey = json_decode($tokenDecrypted, true)['WorkingKey'];


        ## Data
        $data = array(
            'AccountNumber' => env('Asad_Bnc_Account_Number'),
            'ClientID' => env('Asad_Bnc_ClientID'),
            'Amount' => number_format($request->Amount, 2, ',', ' '),
            'BankCode' => $request->BankCode,
            'PhoneNumber' => $request->PhoneNumber,
            'Reference' => $request->Reference,
            "ChildClientID" => "",
            "BranchID" => "",
        );

        $dataJson = json_encode($data);

        ## Encriptado
        $value = self::encrypt($dataJson, $workingKey);

        ## Validation
        $validation = self::createHash($dataJson);

        ## Req
        $req = array("ClientGUID" => $clientGUID, "value" => $value, "Validation" => $validation, "Reference" => "", "swTestOperation" => false);
        $jsonReq = json_encode($req);


        ## Send Post Req
        $gurl = BankUrisApi::URI_VALIDATE_P2P;

        try {
            $gResult = json_decode(self::gPost($gurl, $jsonReq), true);

            if ($gResult && $gResult['status'] == 'OK') {
                $response = self::decrypt($gResult['value'], $workingKey);
                $response = json_decode($response, true);
                return response()->json(['data' => $response, 'status' => 200], 200);
            } else {

                return response()->json(['error' => 'Error procedente del la entidad bancaria', 'status' => 500, 'api_response' => $gResult], 500);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Error procedente del la entidad bancaria',
                'status' => 500,
            ], 404);
        }
    }



    /**
     * Retrieve a list of active banks.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * Valor devuelto: Lista de bancos activos con las siguientes propiedades:
     * ▪ Name: String, es el nombre corto de la institución. Ejemplo: BNC
     * ▪ Code: String, es el código de la institución. Ejemplo: 0191
     * ▪ Services: String, son todos los instrumentos con los que opera la institución.
     *   Ejemplo: TRF, P2P
     */
    public static function banks(): JsonResponse
    {
        $token = self::getToken();
        if (!$token) {
            return response()->json(['error' => 'Token not found'], 404);
        }
        $clientGUID = env('BNC_CLIENT_GUID');
        $masterKey = env('BNC_SECRET_KEY');

        $tokenDecrypted = self::decrypt($token, $masterKey);
        $workingKey = json_decode($tokenDecrypted, true)['WorkingKey'];

        ## Data

        $data = "{}";


        ## Encriptado
        $value = self::encrypt($data, $workingKey);

        ## Validation
        $validation = self::createHash($data);

        ## Req
        $req = array("ClientGUID" => $clientGUID, "value" => $value, "Validation" => $validation, "Reference" => '', "swTestOperation" => false);
        $jsonReq = json_encode($req);

        ## Send Post Req
        $gurl = BankUrisApi::URI_BANKLIST;
        $gResult = json_decode(self::gPost($gurl, $jsonReq), true);

        if ($gResult && $gResult['status'] == 'OK') {
            $response = self::decrypt($gResult['value'], $workingKey);
            $response = json_decode($response, true);
            $banksWithP2P = array_filter($response, function ($bank) {
                return strpos($bank['Services'], 'P2P') !== false;
            });
            return response()->json($banksWithP2P, 200);
        } else {
            return response()->json($gResult, 200);
        }
    }


    /**
     * Sends a P2P payment request.
     *
    //  * @param SendP2PRequest $request The request object containing payment details.
     * @return JsonResponse The JSON response containing the result of the payment request.
     *
     * Valor Devuelto:
     * ▪ Reference: String, referencia de la operación.
     * ▪ AuthorizationCode: String, código autorizador de la operación.
     * ▪ SwAlreadySent: Bool, indica si el pago, identificado con el OperationRef enviado,
     *   se realizó anteriormente con éxito. En caso de ser un pago que ya se haya
     *   realizado con éxito, devolverá true con el Reference y AuthorizationCode de la
     *   operación, en caso contrario, devolverá false con el Reference y AuthorizationCode
     *   de la operación actual.
     */
    public static function sendPay(SendP2PRequest $request): JsonResponse
    {
        $token = self::getToken();
        if (!$token) {
            return response()->json(['error' => 'Token not found'], 404);
        }
        $clientGUID = env('BNC_CLIENT_GUID');
        $masterKey = env('BNC_SECRET_KEY');

        $tokenDecrypted = self::decrypt($token, $masterKey);
        $workingKey = json_decode($tokenDecrypted, true)['WorkingKey'];

        ## Data
        $data = array(
            'AccountNumber' => env('Asad_Bnc_Account_Number'),
            'ClientID' => env('Asad_Bnc_ClientID'),
            'Amount' => $request->Amount,
            'BeneficiaryBankCode' => $request->BeneficiaryBankCode,
            'BeneficiaryCellPhone' => $request->BeneficiaryCellPhone,
            'BeneficiaryID' => $request->BeneficiaryID,
            'BeneficiaryName' => $request->BeneficiaryName,
            'Description' => $request->Description,
            'OperationRef' => $request->OperationRef,
        );

        $dataJson = json_encode($data);

        ## Encriptado
        $value = self::encrypt($dataJson, $workingKey);

        ## Validation
        $validation = self::createHash($dataJson);

        ## Req
        $req = array("ClientGUID" => $clientGUID, "value" => $value, "Validation" => $validation, "Reference" => '', "swTestOperation" => false);
        $jsonReq = json_encode($req);

        ## Send Post Req
        $gurl = BankUrisApi::URI_SENDP2P;
        $gResult = json_decode(self::gPost($gurl, $jsonReq), true);

        if ($gResult && $gResult['status'] && $gResult['status'] == 'OK') {
            $response = self::decrypt($gResult['value'], $workingKey);


            $response = json_decode($response, true);
            return response()->json(['bank_response' => $response], 200);
        } else {
            return response()->json($gResult, 200);
        }
    }
}
