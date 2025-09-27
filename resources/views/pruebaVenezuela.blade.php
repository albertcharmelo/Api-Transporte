<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Prueba BDV Conciliar Movimiento v2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --bg: #f7f7f7;
            --card: #fff;
            --text: #333;
            --primary: #2563eb;
            --muted: #666;
            --border: #e5e7eb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji';
            background: var(--bg);
            color: var(--text);
        }

        .wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 760px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .05);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 22px;
        }

        p.lead {
            margin: 0 0 20px;
            color: var(--muted);
            font-size: 14px;
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            font-size: 13px;
            margin-bottom: 6px;
            color: #222;
        }

        input,
        select,
        button,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
        }

        .row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        button {
            background: var(--primary);
            color: #fff;
            cursor: pointer;
            border: none;
        }

        button:disabled {
            opacity: .7;
            cursor: not-allowed;
        }

        .muted {
            color: var(--muted);
            font-size: 12px;
        }

        pre {
            background: #0b1020;
            color: #e6edf3;
            padding: 14px;
            border-radius: 8px;
            overflow: auto;
            max-height: 320px;
        }

        .status {
            font-size: 13px;
        }

        .ok {
            color: #16a34a;
        }

        .err {
            color: #dc2626;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <h1>Conciliar Movimiento BDV v2</h1>
            <p class="lead">Complete los campos y envíe para probar el endpoint
                PaymentBankController@bdvConciliarMovimientoV2.</p>

            <form id="bdv-form" class="full" autocomplete="off">
                <div>
                    <label for="cedulaPagador">Cédula Pagador</label>
                    <input id="cedulaPagador" name="cedulaPagador" type="text" placeholder="V12345678" required>
                </div>
                <div>
                    <label for="telefonoPagador">Teléfono Pagador</label>
                    <input id="telefonoPagador" name="telefonoPagador" type="text" placeholder="04141234567" required>
                </div>

                <div>
                    <label for="telefonoDestino">Teléfono Destino</label>
                    <input id="telefonoDestino" name="telefonoDestino" type="text" placeholder="04141234567" required>
                </div>
                <div>
                    <label for="referencia">Referencia</label>
                    <input id="referencia" name="referencia" type="text" placeholder="1234567" required>
                </div>

                <div>
                    <label for="fechaPago">Fecha de Pago</label>
                    <input id="fechaPago" name="fechaPago" type="date" required>
                </div>
                <div>
                    <label for="importe">Importe (ej: 10.50)</label>
                    <input id="importe" name="importe" type="number" step="0.01" min="0" placeholder="0.00" required>
                </div>

                <div>
                    <label for="bancoOrigen">Banco Origen</label>
                    <select id="bancoOrigen" name="bancoOrigen" required>
                        <option value="" disabled selected>Seleccione</option>
                        <option value="0102">0102</option>
                        <option value="0104">0104</option>
                        <option value="0105">0105</option>
                        <option value="0108">0108</option>
                        <option value="0114">0114</option>
                        <option value="0115">0115</option>
                        <option value="0128">0128</option>
                        <option value="0134">0134</option>
                        <option value="0137">0137</option>
                        <option value="0138">0138</option>
                        <option value="0146">0146</option>
                        <option value="0151">0151</option>
                        <option value="0156">0156</option>
                        <option value="0157">0157</option>
                        <option value="0163">0163</option>
                        <option value="0168">0168</option>
                        <option value="0169">0169</option>
                        <option value="0171">0171</option>
                        <option value="0172">0172</option>
                        <option value="0173">0173</option>
                        <option value="0174">0174</option>
                        <option value="0175">0175</option>
                        <option value="0177">0177</option>
                        <option value="0178">0178</option>
                        <option value="0191">0191</option>
                    </select>
                    <div class="muted">Códigos válidos según BDV.</div>
                </div>

                <div class="row">
                    <input id="reqCed" name="reqCed" type="checkbox" checked>
                    <label for="reqCed">reqCed (enviar cédula en la verificación)</label>
                </div>

                <div class="actions full">
                    <button id="submitBtn" type="submit">Probar conciliación</button>
                </div>
            </form>

            <div class="full" style="margin-top:16px;">
                <div id="status" class="status muted">Pendiente de envío…</div>
                <pre id="output">// La respuesta JSON aparecerá aquí</pre>
            </div>
        </div>
    </div>

    <script>
        (function(){
    const form = document.getElementById('bdv-form');
    const output = document.getElementById('output');
    const statusEl = document.getElementById('status');
    const submitBtn = document.getElementById('submitBtn');
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Set default date = hoy
    const fecha = document.getElementById('fechaPago');
    if (!fecha.value) {
        const today = new Date();
        const y = today.getFullYear();
        const m = String(today.getMonth() + 1).padStart(2, '0');
        const d = String(today.getDate()).padStart(2, '0');
        fecha.value = `${y}-${m}-${d}`;
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        statusEl.textContent = 'Enviando…';
        statusEl.className = 'status muted';
        output.textContent = '';

        const payload = {
            cedulaPagador: document.getElementById('cedulaPagador').value.trim(),
            telefonoPagador: document.getElementById('telefonoPagador').value.trim(),
            telefonoDestino: document.getElementById('telefonoDestino').value.trim(),
            referencia: document.getElementById('referencia').value.trim(),
            fechaPago: document.getElementById('fechaPago').value, // Y-m-d
            importe: Number(document.getElementById('importe').value || 0).toFixed(2), // "0.00"
            bancoOrigen: document.getElementById('bancoOrigen').value,
            reqCed: document.getElementById('reqCed').checked
        };

        // Validación mínima de front
        if (!payload.bancoOrigen) {
            statusEl.textContent = 'Seleccione un banco de origen.';
            statusEl.className = 'status err';
            return;
        }

        submitBtn.disabled = true;

        try {
            const res = await fetch('{{ route('bdv.conciliar') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify(payload)
            });

            const text = await res.text();
            let json;
            try { json = JSON.parse(text); } catch { json = { raw: text }; }

            output.textContent = JSON.stringify(json, null, 2);
            if (res.ok) {
                statusEl.textContent = 'Solicitud completada.';
                statusEl.className = 'status ok';
            } else {
                statusEl.textContent = `Error HTTP ${res.status}`;
                statusEl.className = 'status err';
            }
        } catch (err) {
            statusEl.textContent = 'Error de red o CORS.';
            statusEl.className = 'status err';
            output.textContent = (err && err.message) ? err.message : String(err);
        } finally {
            submitBtn.disabled = false;
        }
    });
})();
    </script>
</body>

</html>