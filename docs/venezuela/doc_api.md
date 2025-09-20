¡Perfecto! Aquí tienes un texto listo para “alimentar” a Copilot (o pegar en tu repo como `docs/bdv_conciliacion.md`) para que entienda cómo integrar en Laravel la API de conciliación del Banco de Venezuela. Lo dejé **seccionado por funciones**, con **URL, headers, body, validaciones** y **estructura de respuesta**.

---

# Integración BDV – API de Conciliación de Movimientos (Calidad/QA)

## Resumen

La API permite **conciliar pagos** (especialmente Pago Móvil) **en línea y en tiempo real**. El método es **POST** y requiere **API Key** en el header. El ambiente documentado es **Calidad (QA)**.&#x20;

---

## Autenticación y cabeceras (Headers)

-   `X-API-Key`: `96R7T1T5J2134T5YFC2GF15SDFG4BD1Z`

    > Registrar exactamente este header; mover a `.env` en producción.&#x20;

-   `Content-Type`: `application/json`&#x20;

---

## Endpoints

### 1) Conciliación v2 (principal)

-   **Método:** `POST`
-   **URL (QA):** `https://bdvconciliacionqa.banvenez.com:444/getMovement/v2`&#x20;

> Nota: El documento también menciona un endpoint base `.../getMovement` (sin `/v2`), pero la **URL vigente** indicada para QA es la de **`/getMovement/v2`**. Usar v2 como predeterminado.&#x20;

---

## Función 1: `conciliarMovimientoV2`

### Propósito

Enviar una solicitud de conciliación de un pago y obtener el estatus/razón y monto conciliado.&#x20;

### Request

**Headers (obligatorios):**

-   `X-API-Key`: API Key proporcionada por BDV.
-   `Content-Type`: `application/json`.&#x20;

**Body (JSON) – Campos y reglas:**

-   `cedulaPagador` (string): Cédula del pagador, con prefijo de nacionalidad si aplica (p. ej. `"V27037606"`).&#x20;
-   `telefonoPagador` (string): Teléfono de quien **ejecuta** el pago (p. ej. `"0412xxxxxxx"`).&#x20;
-   `telefonoDestino` (string): Teléfono de quien **recibe** el pago.&#x20;
-   `referencia` (string): Referencia de la operación.&#x20;
-   `fechaPago` (string, **formato** `YYYY-MM-DD`): Fecha del pago.

    > **Importante:** Un formato inválido (p. ej. `YYYY/MM/DD`) provoca error con `code = 1010`.&#x20;

-   `importe` (string): Monto con decimales usando **punto** (p. ej. `"120.00"`).&#x20;
-   `bancoOrigen` (string): Código banco origen (p. ej. `"0102"`).&#x20;
-   `reqCed` (boolean):

    -   `true`: **Solo** si se va a validar cédula y la operación es **BDV–BDV**.
    -   `false`: **Obligatorio** para operaciones con **otros bancos** (no se valida cédula).&#x20;

**Ejemplo de body válido (v2):**

```json
{
    "cedulaPagador": "V27037606",
    "telefonoPagador": "04127141363",
    "telefonoDestino": "04127141363",
    "referencia": "123112313",
    "fechaPago": "2023-02-12",
    "importe": "120.00",
    "bancoOrigen": "0102",
    "reqCed": false
}
```

### Response (éxito)

**Estructura JSON esperada:**

-   `code` (number): `1000` = operación exitosa.&#x20;
-   `message` (string): Texto con monto y estatus. Ej.: `"Monto: 120.00 - estatus : Transaccion realizada"`.&#x20;
-   `data` (object):

    -   `status` (string): `"1000"` cuando es exitosa.
    -   `amount` (string): Monto conciliado, ej. `"120.00"`.
    -   `reason` (string): Resultado de la conciliación, ej. `"Transaccion realizada"`.&#x20;

-   `status` (number): `200`. _(El servicio devuelve 200 incluso para algunos errores lógicos; usar `code`/`data.status` para la lógica de negocio)._&#x20;

**Ejemplo (éxito):**

```json
{
    "code": 1000,
    "message": "Monto: 120.00 - estatus : Transaccion realizada",
    "data": {
        "status": "1000",
        "amount": "120.00",
        "reason": "Transaccion realizada"
    },
    "status": 200
}
```

### Response (error de formato o validación)

**Causa típica:** `fechaPago` con formato incorrecto (`YYYY/MM/DD` en vez de `YYYY-MM-DD`).
**Request de ejemplo inválido:**

```json
{
    "cedulaPagador": "V27037606",
    "telefonoPagador": "04127141363",
    "telefonoDestino": "04127141363",
    "referencia": "123112313",
    "fechaPago": "2023/02/12",
    "importe": "120.00",
    "bancoOrigen": "0102"
}
```

**Respuesta típica (error lógico con HTTP 200):**

```json
{
    "code": 1010,
    "message": "No se pudo validar el movimiento : monto : 120.00 - estatus : Transaccion realizada",
    "data": null,
    "status": 200
}
```

**Notas de manejo de errores:**

-   Tratar `code != 1000` o `data == null` como **fallo de conciliación** a nivel de negocio, aunque `status` sea `200`.
-   Loggear siempre `code`, `message` y el body de entrada normalizado para auditoría.&#x20;

---

## (Opcional) Función 2: `conciliarMovimientoLegacy`

> Solo si se requiere compatibilidad con el endpoint base.

-   **Método:** `POST`
-   **URL (QA):** `https://bdvconciliacionqa.banvenez.com:444/getMovement`
-   **Headers y Body:** **idénticos** a `conciliarMovimientoV2`.

> Preferir **v2**. Mantener esta función solo para backward-compat.&#x20;

---

## Reglas y Validaciones Clave (para Copilot)

1. **Formato de fecha:**

    - Aceptar únicamente `YYYY-MM-DD`. Rechazar otros formatos antes de llamar a la API.&#x20;

2. **Formato de importe:**

    - String con punto decimal (ej. `"120.00"`). No usar coma.&#x20;

3. **`reqCed`:**

    - Si `bancoOrigen` corresponde a BDV y tu flujo exige validación de cédula, permitir `true`; en caso contrario, forzar `false`.&#x20;

4. **Manejo de éxito/fracaso:**

    - Éxito = `code === 1000` **y** `data.status === "1000"`.
    - Fracaso = cualquier otra combinación; propagar `message` y/o `reason`.&#x20;

5. **HTTP 200 con error lógico:**

    - No confiar solo en HTTP status. Usar `code` y `data` para decidir.&#x20;

---

## Contratos propuestos (para guiar a Copilot en Laravel)

> **Objetivo**: que Copilot genere Services/Clients, DTOs y tests automáticamente.

### Interfaces

**`BdvConciliacionClient`**

-   `conciliarMovimientoV2(ConciliacionRequestDTO $dto): ConciliacionResponseDTO`
-   _(Opcional)_ `conciliarMovimientoLegacy(ConciliacionRequestDTO $dto): ConciliacionResponseDTO`

### DTOs

**`ConciliacionRequestDTO`**

```php
class ConciliacionRequestDTO {
  public string $cedulaPagador;      // "V27037606"
  public string $telefonoPagador;    // "0412xxxxxxx"
  public string $telefonoDestino;    // "0412xxxxxxx"
  public string $referencia;         // "123112313"
  public string $fechaPago;          // "YYYY-MM-DD"
  public string $importe;            // "120.00"
  public string $bancoOrigen;        // "0102"
  public bool   $reqCed;             // true solo BDV–BDV
}
```

_(Estructura y reglas basadas en la doc oficial)._&#x20;

**`ConciliacionResponseDTO`**

```php
class ConciliacionResponseDTO {
  public int $code;                  // 1000 = OK
  public string $message;            // "Monto: ... - estatus : ..."
  public ?ConciliacionData $data;    // null en error lógico
  public int $status;                // 200 (incluso en errores lógicos)
}

class ConciliacionData {
  public string $status;             // "1000" = OK
  public string $amount;             // "120.00"
  public string $reason;             // "Transaccion realizada"
}
```

_(Estructura y valores según la doc de respuesta)._&#x20;

### Configuración (.env / config)

-   `.env`

    ```
    BDV_BASE_URL=https://bdvconciliacionqa.banvenez.com:444
    BDV_API_KEY=96R7T1T5J2134T5YFC2GF15SDFG4BD1Z
    ```

-   `config/services.php`

    ```php
    'bdv' => [
      'base_url' => env('BDV_BASE_URL'),
      'api_key'  => env('BDV_API_KEY'),
      'timeout'  => 15,
    ],
    ```

_(API Key y base URL tomadas del documento de QA; mover a variables de entorno para otros ambientes.)_&#x20;

### Reglas de validación Laravel (antes de llamar)

```php
[
  'cedulaPagador'   => ['required','string'],
  'telefonoPagador' => ['required','string'],
  'telefonoDestino' => ['required','string'],
  'referencia'      => ['required','string'],
  'fechaPago'       => ['required','date_format:Y-m-d'], // evitar 1010
  'importe'         => ['required','regex:/^\d+\.\d{2}$/'],
  'bancoOrigen'     => ['required','string'],
  'reqCed'          => ['required','boolean'],
]
```

_(La validación de fecha y formato de importe sigue la guía y ejemplos de error.)_&#x20;

---

## Tabla rápida (para Copilot)

| Función               | Método | URL (QA)             | Headers                                       | Body requerido         | Éxito                              | Error lógico                            |
| --------------------- | ------ | -------------------- | --------------------------------------------- | ---------------------- | ---------------------------------- | --------------------------------------- |
| conciliarMovimientoV2 | POST   | `.../getMovement/v2` | `X-API-Key`, `Content-Type: application/json` | Campos listados arriba | `code=1000` y `data.status="1000"` | `code != 1000` o `data=null` (HTTP 200) |

_(Basado íntegramente en el documento de “API Conciliación (Calidad)”)._&#x20;

---

## Observaciones finales

-   **Ambiente:** Este documento es de **Calidad (QA)**; confirmar con BDV la URL/credenciales de **Producción** antes del go-live.&#x20;
-   **Control de versiones:** El documento refleja cambios en URL y en el campo `reqCed` (entradas 2024-10-18, 2024-11-13 y 2025-03-17). Ajustar clientes si cambia la versión.&#x20;

---

¿Quieres que además te deje un **Service** en Laravel (Guzzle/HTTP client) con tests listos para que Copilot los extienda? Puedo entregarlo ya mismo y lo pegas tal cual.
