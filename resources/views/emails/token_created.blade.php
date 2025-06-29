<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Token de Registro - IncubaTec ITTG</title>
    <style>
        /* Estilos generales para el cuerpo del correo */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            background-color: #f8f9fa; /* Fondo más claro para contraste */
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%; /* Ajuste de tamaño de texto para móviles */
            -ms-text-size-adjust: 100%;
            width: 100% !important; /* Asegura el ancho completo */
        }

        /* Contenedor principal del correo, para el fondo */
        .email-wrapper {
            width: 100%;
            background-color: #f8f9fa;
            padding: 20px 0; /* Espaciado superior e inferior para el diseño general */
        }

        /* Contenedor central del contenido del correo (el "card") */
        .email-container {
            max-width: 600px; /* Ancho estándar para correos */
            margin: 0 auto; /* Centrar el contenedor */
            background-color: #ffffff; /* Fondo blanco para el contenido */
            padding: 30px; /* Relleno generoso dentro de la "tarjeta" */
            border-radius: 8px; /* Bordes suavemente redondeados */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Sombra suave para un efecto elevado */
            border: 1px solid #e9ecef; /* Borde muy ligero */
            box-sizing: border-box; /* Incluir el padding en el cálculo del ancho */
        }

        /* Estilos para el encabezado del correo */
        .header {
            text-align: center;
            padding-bottom: 25px; /* Más espacio debajo del encabezado */
            border-bottom: 1px solid #e9ecef; /* Línea separadora más suave */
            margin-bottom: 25px; /* Espacio entre el encabezado y el contenido */
        }
        .header h1 {
            color: #343a40; /* Color de texto más oscuro para el título principal */
            font-size: 28px; /* Título más grande */
            margin: 0;
            padding: 0;
        }
        .header img {
            margin-bottom: 20px;
            max-width: 100px; /* Asegura que el logo no sea demasiado grande */
            height: auto;
            display: block; /* Para centrar la imagen */
            margin-left: auto;
            margin-right: auto;
        }

        /* Área de contenido principal */
        .content {
            padding: 0 5px; /* Pequeño padding horizontal para el texto dentro del contenedor */
            line-height: 1.8; /* Mayor espacio entre líneas para legibilidad */
            color: #495057; /* Color de texto ligeramente más suave */
            font-size: 15px;
        }
        .content p {
            margin-bottom: 18px; /* Más espacio entre párrafos */
        }
        .content strong {
            color: #343a40; /* Color más fuerte para texto en negrita */
        }

        /* Caja del token */
        .token-box {
            background-color: #eaf6ff; /* Fondo azul claro */
            border: 1px dashed #a4d2ed; /* Borde punteado para destacarlo */
            padding: 20px; /* Más relleno */
            text-align: center;
            font-size: 22px; /* Fuente del token ligeramente más grande */
            font-weight: bold;
            color: #007bff; /* Color azul primario */
            border-radius: 6px;
            margin: 25px 0; /* Más margen alrededor de la caja del token */
            word-break: break-all; /* Asegura que los tokens largos se ajusten */
        }

        /* Estilo del botón */
        .button-wrapper {
            text-align: center;
            margin-top: 30px; /* Espacio encima del botón */
        }
        .button {
            display: inline-block;
            background-color: #007bff; /* Azul primario de Bootstrap */
            color: #ffffff !important; /* Fuerza el color de texto blanco */
            padding: 12px 25px; /* Relleno generoso para el botón */
            text-decoration: none; /* Sin subrayado */
            border-radius: 30px; /* Forma de píldora para el botón */
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            border: 1px solid #007bff; /* Borde que coincide con el color de fondo */
            /* Transiciones para efectos hover (no todos los clientes de correo lo soportan) */
            -webkit-transition: all 0.2s ease-in-out;
            -o-transition: all 0.2s ease-in-out;
            transition: all 0.2s ease-in-out;
        }
        .button:hover {
            background-color: #0056b3; /* Azul más oscuro al pasar el ratón */
            border-color: #0056b3;
        }

        /* Estilos del pie de página */
        .footer {
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
            font-size: 13px;
            color: #868e96; /* Color de texto gris suave */
            margin-top: 30px;
        }

        /* Ajustes responsivos para pantallas más pequeñas (media queries) */
        @media only screen and (max-width: 600px) {
            .email-container {
                padding: 20px;
                margin: 0 10px; /* Pequeño margen en pantallas muy pequeñas */
            }
            .header h1 {
                font-size: 24px;
            }
            .content {
                font-size: 14px;
            }
            .token-box {
                font-size: 18px;
                padding: 15px;
            }
            .button {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="header">
                <img src="https://edu.ieee.org/mx-ittg/wp-content/uploads/sites/536/image-768x755.png" alt="Logo de IncubaTec ITTG">
                <h1>¡Bienvenido a IncubaTec ITTG!</h1>
            </div>
            <div class="content">
                <p>Estimado(a) usuario(a) de correo <strong>{{ $correo }}</strong>,</p>
                <p>Se ha generado un token de registro para usted, asignado con el rol de <strong>{{ $rolName }}</strong>.</p>
                <p>Por favor, utilice el siguiente token para completar su registro:</p>
                <div class="token-box">
                    {{ $token }}
                </div>
                <p>Puede hacer clic en el siguiente botón para ir directamente a la página de registro:</p>
                <div class="button-wrapper">
                    <a href="{{ $actionUrl }}" class="button">{{ $actionText }}</a>
                </div>
                <p>Si tiene algún problema o no solicitó este token, por favor ignore este correo.</p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} IncubaTec ITTG. Todos los derechos reservados.</p>
                <p>Este es un correo automático, por favor no responda.</p>
            </div>
        </div>
    </div>
</body>
</html>
