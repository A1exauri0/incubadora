<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FICHA TÉCNICA</title>
    <style>
        @font-face {
            font-family: 'Poppins';
            font-weight: normal;
            src: url('{{ public_path('fonts/Poppins-ExtraLight.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Poppins';
            font-weight: bold;
            src: url('{{ public_path('fonts/Poppins-SemiBold.ttf') }}') format('truetype');
        }

        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 0.8;
        }

        .s1 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 10pt;
        }

        h1 {
            color: black;
            font-style: normal;
            text-decoration: none;
            font-size: 9pt;
        }

        .s2 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 9pt;
        }

        .s3 {
            color: black;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 9pt;
        }

        p {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 9pt;
            margin: 0pt;
        }

        .s4 {
            color: black;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 9pt;
        }

        table,
        tbody {
            vertical-align: top;
            overflow: visible;
        }
    </style>
</head>

<body>
    <center>
        <p class="s1" style="padding-left: 107pt;text-indent: 0pt;text-align: left;"><span>
                <br>
                <center>
                    <img width="50" height="65" src="{{ public_path('images/logo_tec.png') }}" alt="Logo Tec" />
                </center>
            </span> <span>
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td></td>
                    </tr>
                </table>
            </span></p>
        <h1 style="padding-left: 20pt;text-indent: 0pt;text-align: left;">Cumbre Nacional de Desarrollo Tecnológico,
            Emprendimiento e Innovación, InnovaTecNM 2024 </h1>
        <h1 style="padding-left: 20pt;text-indent: 0pt;text-align: left;">
            Instituto Tecnológico de Tuxtla Gutiérrez
        </h1>
        <h1 style="padding-left: 20pt;text-indent: 0pt;text-align: left;">Etapa Local. Región 7</h1>
        <h1 style="padding-bottom: 2pt;text-indent: 0pt;text-align: center;">FICHA TÉCNICA DEL PROYECTO</h1>

        {{-- Tabla de datos --}}
        <table style="border-collapse:collapse;margin-left:20pt;" cellspacing="0">
            <tr style="height:15pt">
                <td style="width:100pt;border-top-style:solid;border-top-width:1pt;border-top-color:#BDBDBD;border-left-style:solid;border-left-width:1pt;border-left-color:#BDBDBD;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BDBDBD;border-right-style:solid;border-right-width:1pt;border-right-color:#BDBDBD"
                    bgcolor="#F4F4F4">
                    <p class="s2" style="padding-top: 1pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                        Nombre
                        corto:</p>
                </td>
                <td style="width:455pt;border-top-style:solid;border-top-width:1pt;border-top-color:#BDBDBD;border-left-style:solid;border-left-width:1pt;border-left-color:#BDBDBD;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BDBDBD;border-right-style:solid;border-right-width:1pt;border-right-color:#BDBDBD"
                    colspan="3">
                    <p class="s2" style="padding-top: 1pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">
                        {{ $proyecto->nombre }}</p>
                    </p>
                </td>
            </tr>
            <tr style="height:15pt">
                <td style="width:100pt;border-top-style:solid;border-top-width:1pt;border-top-color:#BDBDBD;border-left-style:solid;border-left-width:1pt;border-left-color:#BDBDBD;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BDBDBD;border-right-style:solid;border-right-width:1pt;border-right-color:#BDBDBD"
                    bgcolor="#F4F4F4">
                    <p class="s2" style="padding-top: 1pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                        Nombre
                        descriptivo:</p>
                </td>
                <td style="width:455pt;border-top-style:solid;border-top-width:1pt;border-top-color:#BDBDBD;border-left-style:solid;border-left-width:1pt;border-left-color:#BDBDBD;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BDBDBD;border-right-style:solid;border-right-width:1pt;border-right-color:#BDBDBD"
                    colspan="3">
                    <p class="s2" style="padding-top: 1pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">
                        {{ $proyecto->nombre_descriptivo }}</p>

                </td>
            </tr>
            <tr style="height:15pt">
                <td style="width:100pt;border-top-style:solid;border-top-width:1pt;border-top-color:#BDBDBD;border-left-style:solid;border-left-width:1pt;border-left-color:#BDBDBD;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BDBDBD;border-right-style:solid;border-right-width:1pt;border-right-color:#BDBDBD"
                    bgcolor="#F4F4F4">
                    <p class="s2" style="padding-top: 1pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                        Categoría:
                    </p>
                </td>
                <td style="width:455pt;border-top-style:solid;border-top-width:1pt;border-top-color:#BDBDBD;border-left-style:solid;border-left-width:1pt;border-left-color:#BDBDBD;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BDBDBD;border-right-style:solid;border-right-width:1pt;border-right-color:#BDBDBD"
                    colspan="3">
                    <p class="s2" style="padding-top: 1pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">
                        @foreach ($categorias as $categoria)
                            @if ($proyecto->categoria === $categoria->idCategoria)
                                {{ $categoria->nombre }}
                            @endif
                        @endforeach
                    </p>
                </td>
            </tr>
            <tr style="height:15pt">
                <td style="width:100pt;border-top-style:solid;border-top-width:1pt;border-top-color:#BDBDBD;border-left-style:solid;border-left-width:1pt;border-left-color:#BDBDBD;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BDBDBD;border-right-style:solid;border-right-width:1pt;border-right-color:#BDBDBD"
                    bgcolor="#F4F4F4">
                    <p class="s2" style="padding-top: 1pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">Área
                        de
                        aplicación:</p>
                </td>
                <td style="width:455pt;border-top-style:solid;border-top-width:1pt;border-top-color:#BDBDBD;border-left-style:solid;border-left-width:1pt;border-left-color:#BDBDBD;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BDBDBD;border-right-style:solid;border-right-width:1pt;border-right-color:#BDBDBD"
                    colspan="3">
                    <p class="s2" style="padding-top: 1pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">
                        {{ $proyecto->area_aplicacion }}</p>
                </td>
            </tr>
            <tr style="height:15pt">
                <td style="width:100pt;border-top-style:solid;border-top-width:1pt;border-top-color:#BDBDBD;border-left-style:solid;border-left-width:1pt;border-left-color:#BDBDBD;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BDBDBD;border-right-style:solid;border-right-width:1pt;border-right-color:#BDBDBD"
                    bgcolor="#F4F4F4">
                    <p class="s2" style="padding-top: 1pt;padding-left: 2pt;text-indent: 0pt;text-align: left;">
                        Naturaleza
                        técnica:</p>
                </td>
                <td style="width:455pt;border-top-style:solid;border-top-width:1pt;border-top-color:#BDBDBD;border-left-style:solid;border-left-width:1pt;border-left-color:#BDBDBD;border-bottom-style:solid;border-bottom-width:1pt;border-bottom-color:#BDBDBD;border-right-style:solid;border-right-width:1pt;border-right-color:#BDBDBD"
                    colspan="3">
                    <p class="s2" style="padding-top: 1pt;padding-right: 1pt;text-indent: 0pt;text-align: center;">
                        {{ $proyecto->naturaleza_tecnica }}</p>

                </td>
            </tr>
        </table>
        <p style="padding-top: 2pt;text-indent: 0pt;text-align: left;"><br /></p>

        {{-- Objetivo --}}
        <div class="textbox"
            style="background:#F4F4F4;border:0.6pt solid #AAAAAA;min-height:14.2pt;width:556pt;margin-left:20pt;">
            <p class="s3" style="padding-top: 1pt;text-align: center;">Objetivo del proyecto</p>
        </div>
        <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;" />
        <p style="padding-top: 3pt;padding-left: 25pt;margin-right: 20pt;text-indent: 0pt;text-align: justify;">
            {{ $proyecto->objetivo }}
        </p>
        <p style="text-indent: 0pt;text-align: left;"><br /></p>

        {{-- Descripcion --}}
        <div class="textbox"
            style="background:#F4F4F4;border:0.6pt solid #AAAAAA;min-height:14.2pt;width:556pt;margin-left:20pt;">
            <p class="s3" style="padding-top: 1pt;text-align: center;">Descripción general de la
                problemática identificada</p>
        </div>
        <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;" />
        <p style="padding-top: 3pt;padding-left: 25pt;margin-right: 20pt;text-indent: 0pt;text-align: justify;">
            {{ $proyecto->descripcion }}
        </p>
        <p style="text-indent: 0pt;text-align: left;"><br /></p>

        {{-- Resultados --}}
        <div class="textbox"
            style="background:#F4F4F4;border:0.6pt solid #AAAAAA;min-height:14.2pt;width:556pt;margin-left:20pt;">
            <p class="s3" style="padding-top: 1pt;text-align: center;">
                Resultados que se pretenden alcanzar con el desarrollo del proyecto
            </p>
        </div>
        @if ($resultados->isEmpty())
            <br>
            <p style="padding-left: 25pt; text-indent: 0pt; text-align: left;">Sin registros</p>
        @else
            @foreach ($resultados as $resultado)
                <p style="padding-left: 25pt; text-indent: 0pt; text-align: left; margin-top: 8px;">
                    {{ $resultado->descripcion ?? 'Sin registros' }}
                </p>
            @endforeach
        @endif



        {{-- Autores --}}
        <p style="padding-top: 7pt;"><br /></p>
        <table style="border-collapse: collapse; margin-left: 20pt;" cellspacing="0">
            <tr>
                <td style="width: 100%; border: 1pt solid #AAAAAA; background-color: #F4F4F4; text-align: center; vertical-align: middle;"
                    colspan="5">
                    <p style="margin: 0; font-weight: bold;">Autores</p>
                </td>
            </tr>
            @foreach ($alumno_proyecto as $alumno)
                <tr style="height:26pt">
                    {{-- Nombre --}}
                    <td style="width:172pt; border: 1pt solid #BDBDBD;">
                        <p class="s2" style="padding-top: 0pt; text-align: center;">
                            {{ $alumno->nombre }}
                        </p>
                    </td>
                    {{-- Carrera --}}
                    <td style="width:140pt; border: 1pt solid #BDBDBD;">
                        <p class="s2" style="padding-top: 0pt; padding-left: 5pt; text-align: center;">
                            {{ $alumno->carrera }}
                        </p>
                    </td>
                    {{-- Semestre --}}
                    <td style="width:15pt; border: 1pt solid #BDBDBD;">
                        <p class="s2" style="padding-top: 0pt; padding-right: 1pt; text-align: center;">
                            {{ $alumno->semestre }}
                        </p>
                    </td>
                    {{-- Correo institucional --}}
                    <td style="width:155pt; border: 1pt solid #BDBDBD;">
                        <p class="s2" style="padding-top: 0pt; padding-left: 5pt; text-align: center;">
                            {{ $alumno->correo_institucional }}
                        </p>
                    </td>
                    {{-- Teléfono --}}
                    <td style="width:71pt; border: 1pt solid #BDBDBD;">
                        <p class="s2" style="padding-top: 0pt; padding-left: 5pt; text-align: center;">
                            {{ $alumno->telefono }}
                        </p>
                    </td>
                </tr>
            @endforeach
        </table>

        {{-- Asesores --}}
        </table>
        <p style="padding-top: 2pt;text-indent: 0pt;text-align: left;"><br /></p>
        <table style="border-collapse:collapse; margin-left:20pt" cellspacing="0">
            <tr style="height:14pt">
                <td style="width:567pt; border:1pt solid #AAAAAA;" colspan="3" bgcolor="#F4F4F4">
                    <p class="s3" style="padding-top: 1pt; text-indent: 0pt; text-align: center;">Asesores</p>
                </td>
            </tr>
            @foreach ($asesor_proyecto as $asesor)
                <tr style="height:14pt">
                    <td style="width:200pt; border:1pt solid #AAAAAA;">
                        <p class="s2" style="text-indent: 0pt; text-align: center;">
                            {{ $asesor->nombre }}
                        </p>
                    </td>
                    <td style="width:205pt; border:1pt solid #AAAAAA;">
                        <p style="text-indent: 0pt; text-align: center;">
                            {{ $asesor->correo_electronico }}
                        </p>
                    </td>
                    <td style="width:150pt; border:1pt solid #AAAAAA;">
                        <p style="text-indent: 0pt; text-align: center;">
                            {{ $asesor->telefono }}
                        </p>
                    </td>
                </tr>
            @endforeach
        </table>
        {{-- Requerimentos --}}
        <p style="padding-top: 2pt;text-indent: 0pt;text-align: left;"><br /></p>
        <table style="border-collapse:collapse;margin-left:20pt" cellspacing="0">
            <tr style="height:12pt">
                <td style="width:567pt;border:1pt solid #AAAAAA;" colspan="2" bgcolor="#F4F4F4">
                    <p class="s3" style="padding-top: 1pt;text-align: center;">Requerimentos</p>
                </td>
            </tr>

            @if ($requerimientos->isEmpty())
                <tr style="height:14pt">
                    <td style="width:283pt;border:1pt solid #AAAAAA;" bgcolor="#F4F4F4">
                        <p class="s2" style="text-align: center;">
                            Sin registros </p>
                    </td>
                    <td style="width:273pt;border:1pt solid #AAAAAA;">
                        <p class="s2" style="text-align: center;">
                            - </p>
                    </td>
                </tr>
            @else
                @foreach ($requerimientos as $requerimiento)
                    <tr style="height:14pt">
                        <td style="width:283pt;border:1pt solid #AAAAAA;" bgcolor="#F4F4F4">
                            <p class="s2" style="text-align: center;">
                                {{ $requerimiento->descripcion }}
                            </p>
                        </td>
                        <td style="width:273pt;border:1pt solid #AAAAAA;">
                            <p class="s2" style="text-align: center;">
                                {{ $requerimiento->cantidad }}
                            </p>
                        </td>
                    </tr>
                @endforeach
            @endif
        </table>

    </center>

</body>

</html>