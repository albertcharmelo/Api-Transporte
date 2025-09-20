# Flujo de bancos en PaymentBankController

Este documento resume el flujo actual del controlador `PaymentBankController` y diferencia claramente las secciones para BNC y Banco de Venezuela (BDV).

## Secciones del controlador

-   Sección BNC (Banco Nacional de Crédito)

    -   Comentario en el archivo: "SECCION: BNC (Banco Nacional de Crédito)".
    -   Funcionalidades:
        -   `authenticate()`: Obtiene y persiste token cifrado.
        -   `getToken()`: Recupera token vigente o fuerza autenticación.
        -   `historial()`: Consulta movimientos.
        -   `ValidateP2P()`: Valida un pago P2P recibido (consulta existencia de movimiento).
        -   `sendPay()`: Envía pago P2P.
        -   Utilidades: `refere()`, `createHash()`, `gPost()`, `encrypt()`, `decrypt()`.
    -   URIs consumidos: definidos en `App\BankUrisApi`.

-   Sección BDV (Banco de Venezuela)
    -   Comentario en el archivo: "SECCION: Banco de Venezuela (BDV)".
    -   Documentación de referencia: `docs/venezuela/doc_api.md`.
    -   Funcionalidades:
        -   `bdvConciliarMovimientoV2(Request $request)`: Conciliación principal contra `/getMovement/v2`.
        -   `bdvConciliarMovimientoLegacy(Request $request)`: Conciliación legacy contra `/getMovement`.
    -   Configuración requerida en `config/services.php`:
        -   `services['bdv'] = ['base_url' => env('BDV_BASE_URL'), 'api_key' => env('BDV_API_KEY'), 'timeout' => 15]`.

## Rutas API

Archivo `routes/api.php`:

-   Grupo `bank` (existente):

    -   BNC

        -   POST `bank/consultar` -> `PaymentBankController@consultar` (si aplica)
        -   POST `bank/historial` -> `PaymentBankController@historial`
        -   POST `bank/banklist` -> `PaymentBankController@banks`
        -   POST `bank/payp2p` -> `PaymentBankController@sendPay`
        -   POST `bank/payp2pconfirm` -> `PaymentBankController@ValidateP2P`

    -   Banco de Venezuela (nuevo)
        -   POST `bank/bdv/conciliar/v2` -> `PaymentBankController@bdvConciliarMovimientoV2`
        -   POST `bank/bdv/conciliar/legacy` -> `PaymentBankController@bdvConciliarMovimientoLegacy`

## Validaciones clave (BDV)

Antes de llamar a los endpoints BDV se valida:

-   `fechaPago` con formato `Y-m-d` (evitar código 1010 por formato inválido).
-   `importe` en formato `"120.00"` usando punto decimal.
-   `reqCed` booleano: true solo BDV–BDV, false en otros bancos.

## Variables de entorno

Agregar en `.env`:

```
BDV_BASE_URL=https://bdvconciliacionqa.banvenez.com:444
BDV_API_KEY=XXXX
```

Y en `config/services.php`:

```
'bdv' => [
  'base_url' => env('BDV_BASE_URL'),
  'api_key'  => env('BDV_API_KEY'),
  'timeout'  => 15,
],
```

## Manejo de respuestas (BDV)

-   El servicio suele devolver HTTP 200 incluso en errores lógicos.
-   Éxito de negocio cuando: `code === 1000` y `data.status === "1000"`.
-   Se devuelve `success: true|false` en la respuesta local para simplificar el consumo.

## Auditoría y logs

Los errores en la sección BDV disparan `RegisterAppLog` con datos relevantes (referencia, monto, banco, teléfono, usuario) para trazabilidad.
