<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pruebas Bancarias - BDV y BNC</title>
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
            --tab-active: #2563eb;
            --tab-inactive: #9ca3af;
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
            max-width: 900px;
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

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            margin-bottom: 24px;
        }

        .tab {
            padding: 12px 24px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            color: var(--tab-inactive);
            font-weight: 500;
            transition: all 0.2s;
        }

        .tab:hover {
            color: var(--primary);
        }

        .tab.active {
            color: var(--tab-active);
            border-bottom-color: var(--tab-active);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
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
            <h1>Pruebas Bancarias - BDV y BNC</h1>
            <p class="lead">Seleccione el banco y complete los campos para probar los endpoints de conciliación.</p>

            <!-- Tabs -->
            <div class="tabs">
                <div class="tab active" data-tab="bdv">Banco de Venezuela (BDV)</div>
                <div class="tab" data-tab="bnc">Banco Nacional de Crédito (BNC)</div>
            </div>

            <!-- Tab BDV -->
            <div id="bdv-tab" class="tab-content active">
                <h2>Conciliar Movimiento BDV v2</h2>
                <p class="lead">Complete los campos para probar el endpoint PaymentBankController@bdvConciliarMovimientoV2.</p>

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
                        <button id="bdv-submitBtn" type="submit">Probar conciliación BDV</button>
                    </div>
                </form>

                <div class="full" style="margin-top:16px;">
                    <div id="bdv-status" class="status muted">Pendiente de envío…</div>
                    <pre id="bdv-output">// La respuesta JSON aparecerá aquí</pre>
                </div>
            </div>

            <!-- Tab BNC -->
            <div id="bnc-tab" class="tab-content">
                <h2>Validar P2P BNC</h2>
                <p class="lead">Complete los campos para probar el endpoint PaymentBankController@ValidateP2P.</p>

                <form id="bnc-form" class="full" autocomplete="off">
                    <div>
                        <label for="Amount">Monto</label>
                        <input id="Amount" name="Amount" type="number" step="0.01" min="0" placeholder="100.00" required>
                        <div class="muted">Formato: 100.00 (máximo 11 dígitos enteros, 2 decimales)</div>
                    </div>
                    <div>
                        <label for="BankCode">Código del Banco</label>
                        <input id="BankCode" name="BankCode" type="text" placeholder="191" maxlength="4" required>
                        <div class="muted">Ejemplo: 191 (máximo 4 caracteres)</div>
                    </div>

                    <div>
                        <label for="PhoneNumber">Número de Teléfono</label>
                        <input id="PhoneNumber" name="PhoneNumber" type="text" placeholder="584249999999" required>
                        <div class="muted">Formato: 58XXXXXXXXXX (58 + 10 dígitos)</div>
                    </div>
                    <div>
                        <label for="Reference">Referencia</label>
                        <input id="Reference" name="Reference" type="text" placeholder="REF123456" required>
                    </div>

                    <div class="actions full">
                        <button id="bnc-submitBtn" type="submit">Probar validación BNC</button>
                    </div>
                </form>

                <div class="full" style="margin-top:16px;">
                    <div id="bnc-status" class="status muted">Pendiente de envío…</div>
                    <pre id="bnc-output">// La respuesta JSON aparecerá aquí</pre>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Tab functionality
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const targetTab = tab.getAttribute('data-tab');
                    
                    // Remove active class from all tabs and contents
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(tc => tc.classList.remove('active'));
                    
                    // Add active class to clicked tab and corresponding content
                    tab.classList.add('active');
                    document.getElementById(targetTab + '-tab').classList.add('active');
                });
            });

            // Set default date for BDV form
            const fecha = document.getElementById('fechaPago');
            if (fecha && !fecha.value) {
                const today = new Date();
                const y = today.getFullYear();
                const m = String(today.getMonth() + 1).padStart(2, '0');
                const d = String(today.getDate()).padStart(2, '0');
                fecha.value = `${y}-${m}-${d}`;
            }

            // BDV Form Handler
            const bdvForm = document.getElementById('bdv-form');
            const bdvOutput = document.getElementById('bdv-output');
            const bdvStatus = document.getElementById('bdv-status');
            const bdvSubmitBtn = document.getElementById('bdv-submitBtn');

            bdvForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                bdvStatus.textContent = 'Enviando…';
                bdvStatus.className = 'status muted';
                bdvOutput.textContent = '';

                const payload = {
                    cedulaPagador: document.getElementById('cedulaPagador').value.trim(),
                    telefonoPagador: document.getElementById('telefonoPagador').value.trim(),
                    telefonoDestino: document.getElementById('telefonoDestino').value.trim(),
                    referencia: document.getElementById('referencia').value.trim(),
                    fechaPago: document.getElementById('fechaPago').value,
                    importe: Number(document.getElementById('importe').value || 0).toFixed(2),
                    bancoOrigen: document.getElementById('bancoOrigen').value,
                    reqCed: document.getElementById('reqCed').checked
                };

                if (!payload.bancoOrigen) {
                    bdvStatus.textContent = 'Seleccione un banco de origen.';
                    bdvStatus.className = 'status err';
                    return;
                }

                bdvSubmitBtn.disabled = true;

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

                    bdvOutput.textContent = JSON.stringify(json, null, 2);
                    if (res.ok) {
                        bdvStatus.textContent = 'Solicitud completada.';
                        bdvStatus.className = 'status ok';
                    } else {
                        bdvStatus.textContent = `Error HTTP ${res.status}`;
                        bdvStatus.className = 'status err';
                    }
                } catch (err) {
                    bdvStatus.textContent = 'Error de red o CORS.';
                    bdvStatus.className = 'status err';
                    bdvOutput.textContent = (err && err.message) ? err.message : String(err);
                } finally {
                    bdvSubmitBtn.disabled = false;
                }
            });

            // BNC Form Handler
            const bncForm = document.getElementById('bnc-form');
            const bncOutput = document.getElementById('bnc-output');
            const bncStatus = document.getElementById('bnc-status');
            const bncSubmitBtn = document.getElementById('bnc-submitBtn');

            bncForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                bncStatus.textContent = 'Enviando…';
                bncStatus.className = 'status muted';
                bncOutput.textContent = '';

                const payload = {
                    Amount: parseFloat(document.getElementById('Amount').value),
                    BankCode: document.getElementById('BankCode').value.trim(),
                    PhoneNumber: document.getElementById('PhoneNumber').value.trim(),
                    Reference: document.getElementById('Reference').value.trim()
                };

                // Validación básica
                if (!payload.Amount || payload.Amount <= 0) {
                    bncStatus.textContent = 'El monto debe ser mayor a 0.';
                    bncStatus.className = 'status err';
                    return;
                }

                if (!payload.BankCode || payload.BankCode.length > 4) {
                    bncStatus.textContent = 'El código del banco debe tener máximo 4 caracteres.';
                    bncStatus.className = 'status err';
                    return;
                }

                if (!payload.PhoneNumber.startsWith('58') || payload.PhoneNumber.length !== 12) {
                    bncStatus.textContent = 'El teléfono debe tener formato 58XXXXXXXXXX.';
                    bncStatus.className = 'status err';
                    return;
                }

                bncSubmitBtn.disabled = true;

                try {
                    const res = await fetch('/api/bank/payp2pconfirm', {
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

                    bncOutput.textContent = JSON.stringify(json, null, 2);
                    if (res.ok) {
                        bncStatus.textContent = 'Solicitud completada.';
                        bncStatus.className = 'status ok';
                    } else {
                        bncStatus.textContent = `Error HTTP ${res.status}`;
                        bncStatus.className = 'status err';
                    }
                } catch (err) {
                    bncStatus.textContent = 'Error de red o CORS.';
                    bncStatus.className = 'status err';
                    bncOutput.textContent = (err && err.message) ? err.message : String(err);
                } finally {
                    bncSubmitBtn.disabled = false;
                }
            });
        })();
    </script>
</body>

</html>