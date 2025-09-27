<?php

namespace App\Http\Controllers;

use App\AppLog;
use App\BankUrisApi;
use App\BncToken;
use App\Events\RegisterAppLog;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendP2PRequest;
use App\Http\Requests\ValidateP2PRequest;
use App\Liquidacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class PaymentBankController extends Controller
{
    /** Lista blanca de bancos permitidos para BDV (bancoOrigen). */
    private const BDV_BANK_CODES = [
        '0102',
        '0104',
        '0105',
        '0108',
        '0114',
        '0115',
        '0128',
        '0134',
        '0137',
        '0138',
        '0146',
        '0151',
        '0156',
        '0157',
        '0163',
        '0168',
        '0169',
        '0171',
        '0172',
        '0173',
        '0174',
        '0175',
        '0177',
        '0178',
        '0191'
    ];


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

    public static function refere()
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
    /**
     * =============================================================
     *  SECCION: BNC (Banco Nacional de Crédito)
     *  Nota: Las funciones existentes en este bloque manejan cifrado,
     *  obtención de token y operaciones específicas del BNC (URIs en
     *  App\BankUrisApi). Mantener esta sección separada de otros bancos.
     * =============================================================
     */



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




    /**
     * =============================================================
     *  SECCION: Banco de Venezuela (BDV)
     *  Fuente de documentación: docs/venezuela/doc_api.md
     *  Características:
     *    - API REST con API Key vía header X-API-Key
     *    - Endpoint principal de conciliación: /getMovement/v2
     *    - Validaciones previas en formato y tipos (fecha, importe)
     *  Configuración en config/services.php: services['bdv']
     * =============================================================
     */

    /**
     * Conciliación de movimiento Pago Móvil – BDV v2
     * POST {base_url}/getMovement/v2
     * Headers: X-API-Key, Content-Type: application/json
     * Body: ver reglas en docs/venezuela/doc_api.md
     */
    public static function bdvConciliarMovimientoV2(Request $request): JsonResponse
    {
        // Validación de entrada según guía oficial (mensajes simples)
        $validated = $request->validate(
            [
                'cedulaPagador'   => ['required', 'string'],
                'telefonoPagador' => ['required', 'string'],
                'telefonoDestino' => ['required', 'string'],
                'referencia'      => ['required', 'string'],
                'fechaPago'       => ['required', 'date_format:Y-m-d'],
                'importe'         => ['required', 'regex:/^\d+\.\d{2}$/'],
                'bancoOrigen'     => ['required', 'string', Rule::in(self::BDV_BANK_CODES)],
                'reqCed'          => ['required', 'boolean'],
            ]
        );

        $baseUrl = rtrim((string) config('services.bdv.base_url'), '/');
        $apiKey  = (string) config('services.bdv.api_key');
        $timeout = (int) (config('services.bdv.timeout') ?? 15);

        if (!$baseUrl || !$apiKey) {
            return response()->json([
                'code' => 500,
                'message' => 'Falta configuración del Banco de Venezuela (services.bdv) para realizar la conciliación.',
                'data' => null,
                'status' => 500,
            ], 500);
        }

        $endpoint = $baseUrl . '/getMovement';

        try {
            $httpResponse = Http::timeout($timeout)
                ->withHeaders([
                    'X-API-Key' => $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($endpoint, $validated);

            $payload = $httpResponse->json();

            // Si la API de BDV devuelve HTTP distinto de 200, propagamos ese status
            if (!$httpResponse->ok()) {
                return response()->json([
                    'code' => $httpResponse->status(),
                    'message' => 'Error HTTP desde BDV',
                    'data' => $payload,
                    'status' => $httpResponse->status(),
                ], $httpResponse->status());
            }

            // Normalización de respuesta según doc: code, message, data{status,amount,reason}, status
            // Éxito de negocio: code === 1000 y data.status === "1000"
            $isBusinessOk = isset($payload['code']) && (int) $payload['code'] === 1000
                && isset($payload['data']['status']) && (string) $payload['data']['status'] === '1000';

            // Devolvemos 200 siempre (como el servicio) pero incluimos la semántica de éxito
            return response()->json([
                'code' => $payload['code'] ?? 0,
                'message' => $payload['message'] ?? 'Sin mensaje',
                'data' => $payload['data'] ?? null,
                'status' => $payload['status'] ?? 200,
                'success' => $isBusinessOk,
            ], 200);
        } catch (\Throwable $th) {
            // Log de app para auditoría
            // event(new RegisterAppLog(
            //     'bdv',
            //     'Error en ' . __CLASS__ . '::' . __FUNCTION__ . ' - ' . ($th->getMessage() ?? 'Null'),
            //     500,
            //     $validated['referencia'] ?? ($request->input('referencia') ?? ''),
            //     (float) ($validated['importe'] ?? 0),
            //     $validated['bancoOrigen'] ?? ($request->input('bancoOrigen') ?? ''),
            //     $validated['telefonoPagador'] ?? ($request->input('telefonoPagador') ?? ''),
            //     auth()->id() ?? 0,
            //     0
            // ));

            return response()->json([
                'code' => 500,
                'message' => 'Excepción al consultar BDV',
                'error' => $th->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    /**
     * (Opcional) Conciliación legacy sin /v2
     */
    public static function bdvConciliarMovimientoLegacy(Request $request): JsonResponse
    {
        $validated = $request->validate(
            [
                'cedulaPagador'   => ['required', 'string'],
                'telefonoPagador' => ['required', 'string'],
                'telefonoDestino' => ['required', 'string'],
                'referencia'      => ['required', 'string'],
                'fechaPago'       => ['required', 'date_format:Y-m-d'],
                'importe'         => ['required', 'regex:/^\d+\.\d{2}$/'],
                'bancoOrigen'     => ['required', 'string', Rule::in(self::BDV_BANK_CODES)],
                'reqCed'          => ['required', 'boolean'],
            ]
        );

        $baseUrl = rtrim((string) config('services.bdv.base_url'), '/');
        $apiKey  = (string) config('services.bdv.api_key');
        $timeout = (int) (config('services.bdv.timeout') ?? 15);

        if (!$baseUrl || !$apiKey) {
            return response()->json([
                'code' => 500,
                'message' => 'Falta configuración del Banco de Venezuela (services.bdv).',
                'data' => null,
                'status' => 500,
            ], 500);
        }

        $endpoint = $baseUrl . '/getMovement';

        try {
            $httpResponse = Http::timeout($timeout)
                ->withHeaders([
                    'X-API-Key' => $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($endpoint, $validated);

            $payload = $httpResponse->json();

            if (!$httpResponse->ok()) {
                return response()->json([
                    'code' => $httpResponse->status(),
                    'message' => 'Error HTTP desde BDV (legacy)',
                    'data' => $payload,
                    'status' => $httpResponse->status(),
                ], $httpResponse->status());
            }

            $isBusinessOk = isset($payload['code']) && (int) $payload['code'] === 1000
                && isset($payload['data']['status']) && (string) $payload['data']['status'] === '1000';

            return response()->json([
                'code' => $payload['code'] ?? 0,
                'message' => $payload['message'] ?? 'Sin mensaje',
                'data' => $payload['data'] ?? null,
                'status' => $payload['status'] ?? 200,
                'success' => $isBusinessOk,
            ], 200);
        } catch (\Throwable $th) {
            event(new RegisterAppLog(
                'bdv',
                'Error en ' . __CLASS__ . '::' . __FUNCTION__ . ' - ' . ($th->getMessage() ?? 'Null'),
                500,
                $validated['referencia'] ?? ($request->input('referencia') ?? ''),
                (float) ($validated['importe'] ?? 0),
                $validated['bancoOrigen'] ?? ($request->input('bancoOrigen') ?? ''),
                $validated['telefonoPagador'] ?? ($request->input('telefonoPagador') ?? ''),
                auth()->id() ?? 0,
                0
            ));

            return response()->json([
                'code' => 500,
                'message' => 'Excepción al consultar BDV (legacy)',
                'error' => $th->getMessage(),
                'status' => 500,
            ], 500);
        }
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

        if (!$gResult) {
            //Error del Banco
            return response()->json(['error' => 'Error procedente del la entidad bancaria', 'status' => 500, $gResult], 500);
        }

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

        if ($gResult && $gResult['status'] == 'OK') {
            $response = self::decrypt($gResult['value'], $workingKey);
            $response = json_decode($response, true);
            return response()->json($response, 200);
        } else {
            return response()->json($gResult, 200);
        }
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
            'Amount' => (float)number_format($request->Amount, 2, ',', ' '),
            'BankCode' => $request->BankCode,
            'PhoneNumber' => $request->PhoneNumber,
            'Reference' => $request->Reference,
            "RequestDate" => "2025-02-11T00:00:00",
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
                if ($response['MovementExists'] === true) {
                    return response()->json(['data' => $response, 'status' => 200], 200);
                }
                return response()->json(['error' => 'No se encontró el movimiento', 'status' => 404], 404);
            } else {
                return response()->json(['error' => 'Error procedente del la entidad bancaria', 'status' => 500, 'api_response' => $gResult, 'data' => $data], 500);
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
            'Amount' => (float)$request->Amount,
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
