<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatutos Legales | ASAD, C.A.</title>
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --text-color: #333333;
            --light-gray: #f3f4f6;
            --border-color: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: #ffffff;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .logo-icon {
            margin-right: 10px;
            font-size: 28px;
        }

        .main-content {
            padding: 40px 0;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .last-updated {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }

        .terms-section {
            margin-bottom: 40px;
            background-color: var(--light-gray);
            padding: 25px;
            border-radius: 8px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 15px;
            color: var(--secondary-color);
        }

        p {
            margin-bottom: 15px;
        }

        ul,
        ol {
            margin-bottom: 15px;
            margin-left: 20px;
        }

        li {
            margin-bottom: 8px;
        }

        .highlight {
            background-color: rgba(37, 99, 235, 0.1);
            padding: 2px 4px;
            border-radius: 4px;
        }

        footer {
            background-color: var(--light-gray);
            padding: 30px 0;
            border-top: 1px solid var(--border-color);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .copyright {
            font-size: 14px;
        }

        .contact-info {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 22px;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .copyright {
                margin-bottom: 15px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="header-content">
                <a href="#" class="logo">
                    <span class="logo-icon"></span>
                    ASAD, C.A.
                </a>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <h1>Estatutos Legales</h1>
            <p class="last-updated">Última actualización: 2 de diciembre de 2024</p>

            <div class="terms-section">
                <h2>1. Definición y Objeto</h2>
                <p>ASAD, C.A es una sociedad mercantil registrada en el Registro Mercantil Primero del Estado Aragua,
                    Venezuela, bajo el expediente N° 283-57897. La empresa desarrolla una aplicación móvil diseñada para
                    optimizar el cobro de pasajes en el transporte público mediante códigos QR, mejorando la eficiencia
                    operativa y la seguridad financiera para transportistas y pasajeros.</p>
            </div>

            <div class="terms-section">
                <h2>2. Uso de la Aplicación</h2>
                <h3>2.1. Para Pasajeros:</h3>
                <ul>
                    <li><strong>Registro:</strong> Descarga la aplicación desde Google Play Store (Android). Creación de
                        cuenta con datos personales (nombre, apellido, cédula, correo electrónico) y contraseña segura.
                    </li>
                    <li><strong>Funcionalidades:</strong>
                        <ul>
                            <li>Visualización de mapa interactivo con líneas de autobuses y precios en tiempo real.</li>
                            <li>Billetera digital con código QR único para pagos.</li>
                            <li>Recarga de saldo mediante transferencia al Banco Nacional de Crédito (BNC), con un cargo
                                adicional del 6% por mantenimiento.</li>
                            <li>Historial de transacciones (egresos, recargas, devoluciones).</li>
                            <li>Generación de nuevo código QR en caso de extravío o robo.</li>
                        </ul>
                    </li>
                </ul>
                <h3>2.2. Para Conductores:</h3>
                <ul>
                    <li><strong>Registro:</strong> Proceso similar al de pasajeros, con verificación adicional de
                        identidad y datos de la unidad de transporte.</li>
                    <li><strong>Funcionalidades:</strong>
                        <ul>
                            <li>Cobro de pasajes mediante escaneo de QR.</li>
                            <li>Eliminación del manejo de efectivo.</li>
                            <li>Historial de transacciones y recepción automática de fondos en cuenta bancaria (2 veces
                                al día).</li>
                            <li>Monitoreo en tiempo real de unidades operativas.</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="terms-section">
                <h2>3. Seguridad y Transacciones</h2>
                <ul>
                    <li><strong>Conexión con BNC:</strong> API con encriptación AES y autenticación mediante llave
                        maestra y llave de trabajo temporal (renovable diariamente). Estructura de peticiones POST con
                        validación SHA256.</li>
                    <li><strong>Protección de Datos:</strong> Los usuarios son responsables de la confidencialidad de
                        sus credenciales. ASAD no almacena datos sensibles de tarjetas bancarias.</li>
                </ul>
            </div>

            <div class="terms-section">
                <h2>4. Tarifas y Comisiones</h2>
                <ul>
                    <li><strong>Pasajeros:</strong> Recarga de saldo con cargo del 6% sobre el monto. 1 ASAD = 1 dólar =
                        1 Bs. (equivalencia fija).</li>
                    <li><strong>Conductores:</strong> Sin comisiones por uso de la aplicación.</li>
                </ul>
            </div>

            <div class="terms-section">
                <h2>5. Responsabilidades</h2>
                <ul>
                    <li><strong>Usuarios:</strong> Verificar la exactitud de los datos registrados. Notificar extravío o
                        robo del dispositivo para regenerar el código QR.</li>
                    <li><strong>ASAD, C.A:</strong> Garantizar la disponibilidad de la plataforma, excluyendo fallas por
                        causas ajenas (ej. cortes de internet). Proteger los datos personales según la ley venezolana.
                    </li>
                </ul>
            </div>

            <div class="terms-section">
                <h2>6. Modificaciones y Aceptación</h2>
                <p>Los presentes términos pueden ser actualizados, notificando a los usuarios mediante la aplicación. El
                    uso continuado de la aplicación implica aceptación de los términos vigentes.</p>
            </div>

            <div class="terms-section">
                <h2>7. Jurisdicción</h2>
                <p>Cualquier controversia se resolverá en los tribunales competentes del Estado Aragua, Venezuela, bajo
                    las leyes de la República Bolivariana de Venezuela.</p>
            </div>

            <div class="terms-section">
                <h2>Documento Legal</h2>
                <p>Documento elaborado conforme al Acta de Asamblea Extraordinaria de ASAD, C.A, inscrita en el Tomo 9-A
                    del Registro Mercantil Primero del Estado Aragua (N° 14, 31/01/2025).</p>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="copyright">
                    &copy; 2024 ASAD, C.A. Todos los derechos reservados.
                </div>
                <div class="contact-info">
                    asadvzla@gmail.com
                </div>
            </div>
        </div>
    </footer>
</body>

</html>