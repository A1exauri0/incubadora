<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de tu Propuesta de Proyecto - IncubaTec ITTG</title>
    <style>
        /* Estilos CSS reutilizados de tu plantilla original */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            width: 100% !important;
        }

        .email-wrapper {
            width: 100%;
            background-color: #f8f9fa;
            padding: 20px 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            padding-bottom: 25px;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #343a40;
            font-size: 28px;
            margin: 0;
            padding: 0;
        }

        .header img {
            margin-bottom: 20px;
            max-width: 100px;
            height: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .content {
            padding: 0 5px;
            line-height: 1.8;
            color: #495057;
            font-size: 15px;
        }

        .content p {
            margin-bottom: 18px;
        }

        .content strong {
            color: #343a40;
        }

        .project-details-box {
            background-color: #eaf6ff;
            border: 1px dashed #a4d2ed;
            padding: 20px;
            font-size: 16px;
            color: #007bff;
            border-radius: 6px;
            margin: 25px 0;
            word-break: break-all;
        }

        .project-details-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .project-details-box ul li {
            margin-bottom: 8px;
        }

        .project-details-box ul li:last-child {
            margin-bottom: 0;
        }

        .button-wrapper {
            text-align: center;
            margin-top: 30px;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff !important;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            border: 1px solid #007bff;
            -webkit-transition: all 0.2s ease-in-out;
            -o-transition: all 0.2s ease-in-out;
            transition: all 0.2s ease-in-out;
        }

        .button:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .footer {
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
            font-size: 13px;
            color: #868e96;
            margin-top: 30px;
        }

        @media only screen and (max-width: 600px) {
            .email-container {
                padding: 20px;
                margin: 0 10px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                font-size: 14px;
            }

            .project-details-box {
                font-size: 14px;
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
                <img src="https://edu.ieee.org/mx-ittg/wp-content/uploads/sites/536/image-768x755.png"
                    alt="Logo de IncubaTec ITTG" width="100" height="auto" style="max-width: 100px;">
                <h1>¡Actualización de tu Propuesta de Proyecto!</h1>
            </div>
            <div class="content">
                <p>Hola, {{ $userName }},</p>
                <p>Tu propuesta de proyecto <strong>"{{ $projectName }}"</strong> ha sido
                    <strong>{{ $newStatus }}</strong>. A continuación, los detalles:</p>

                <div class="project-details-box">
                    <ul>
                        <li><strong>Nombre del Proyecto:</strong> {{ $projectName }}</li>
                        <li><strong>Estado Actual:</strong> {{ $newStatus }}</li>
                        @if ($rejectionReason)
                            <li><strong>Motivo de Rechazo:</strong> {{ $rejectionReason }}</li>
                        @endif
                    </ul>
                </div>

                <p>Puedes ver los detalles completos de tu proyecto en el siguiente enlace:</p>

                <div class="button-wrapper">
                    <a href="{{ $proposalLink }}" class="button">Ver mi Propuesta</a>
                </div>

                <p>Gracias por tu participación.</p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} IncubaTec ITTG. Todos los derechos reservados.</p>
                <p>Este es un correo automático, por favor no responda.</p>
            </div>
        </div>
    </div>
</body>

</html>