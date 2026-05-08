<!DOCTYPE html>
<html>
<head>
    <title>Paz y Salvo {{ $alcaldia }}</title>
    <style>
        @page {
            margin: 0;
        }
        * { padding: 0; margin: 0; }
        @font-face{
            font-family:"LATAM Sans";
            src:url({{ storage_path('fonts/latamsans/latam_sans_regular-webfont.woff') }}) format("woff"),url({{ storage_path('fonts/latamsans/latam_sans_regular-webfont.ttf') }}) format("truetype");
            font-weight:400;
            font-style:normal
        }

        @font-face{
            font-family:"LATAM Sans";
            src:url({{ storage_path('fonts/latamsans/latam_sans_regular_italic-webfont.woff') }}) format("woff"),url({{ storage_path('fonts/latamsans/latam_sans_regular_italic-webfont.ttf') }}) format("truetype");
            font-weight:400;
            font-style:italic
        }

        @font-face{
            font-family:"LATAM Sans";
            src:url({{ storage_path('fonts/latamsans/latam_sans_light-webfont.woff') }}) format("woff"),url({{ storage_path('fonts/latamsans/latam_sans_light-webfont.ttf') }}) format("truetype");
            font-weight:300;
            font-style:normal
        }

        @font-face{
            font-family:"LATAM Sans";
            src:url({{ storage_path('fonts/latamsans/latam_sans_light_italic-webfont.woff') }}) format("woff"),url({{ storage_path('fonts/latamsans/latam_sans_light_italic-webfont.ttf') }}) format("truetype");
            font-weight:300;
            font-style:italic
        }

        @font-face{
            font-family:"LATAM Sans";
            src:url({{ storage_path('fonts/latamsans/latam_sans_bold-webfont.woff') }}) format("woff"),url({{ storage_path('fonts/latamsans/latam_sans_bold-webfont.ttf') }}) format("truetype");
            font-weight:700;
            font-style:normal
        }

        @font-face{
            font-family:"LATAM Sans";
            src:url({{ storage_path('fonts/latamsans/latam_sans_bold_italic-webfont.woff') }}) format("woff"),url({{ storage_path('fonts/latamsans/latam_sans_bold_italic-webfont.ttf') }}) format("truetype");
            font-weight:700;
            font-style:italic
        }

        @font-face{
            font-family:"LATAM Sans Extended";
            src:url({{ storage_path('fonts/latamsans/latam_sans_extended-webfont.woff') }}) format("woff"),url({{ storage_path('fonts/latamsans/latam_sans_extended-webfont.ttf') }}) format("truetype");
            font-weight:400
        }

        html,
        body {
            font-family: 'LATAM Sans';
            padding: 10px;
            margin: 10px;
            /* margin:0;
            padding:0; */
            height:100%;
        }
        #container {
            min-height:100%;
            position:relative;
        }
        #header {
            padding:10px;
        }
        #body {
            padding:10px;
        }
        #footer {
            position:absolute;
            bottom:0;
            width:100%;
            height:40px;   /* Height of the footer */
            text-align: center;
            font-size: 60%;
            border-top: 1px solid #c0c0c0;
        }

		/* div.page { width:100%; padding-top: 5%; padding-bottom: 100px; margin: 0 auto; } */
        table { border-collapse: collapse; font-size: 100%; width: 100%; }
        /* td { padding: 1px; } */
        /* th { padding: 1px; background-color: #f3efef; } */
		/* h3.title { width: 100%; text-align: center; } */

        table.table-header tr td { border: 0px; font-size: 100%; }
        p.header { width: 100%; text-align:center; font-weight: bold; padding: 0px; margin: 0px; font-size: 70%; }
		table.titles tr td { border: 0px; font-size: 80%; text-align: center; }

		table.info-encabezado tr th { text-align:justify; font-size: 80%; }
		table.info-encabezado tr td { text-align:justify; font-size: 70%; }

        table.info-predio tr th { font-size: 60%; border: 1px solid #000; padding: 1px; background-color: #f3efef; }
		table.info-predio tr td { font-size: 60%; border: 1px solid #000; padding: 1px; }
        table tr th.text-left, td.text-left { text-align: left; }
        table tr th.text-center, td.text-center { text-align: center; }
        table tr th.text-right, td.text-right { text-align: right; }

        table.info-resumen tr th { font-size: 60%; border: 1px solid #000; padding: 1px; background-color: #f3efef; }
		table.info-resumen tr td { font-size: 60%; border: 1px solid #000; padding: 1px; }

        table.info-validez tr th { font-size: 60%; border: 0px; padding: 1px; }
		table.info-validez tr td { font-size: 60%; border: 0px; padding: 1px; }

        .info { font-size: 70%; }
        .text-small { font-size: 40%; }
		.negrilla {font-weight: bold;}

    </style>
</head>
<body>
    <div id="container">
        <div id="header">
            <table class="table-header">
                <tr>
                    <td style="width: 20%; font-size: 60%; text-align: center;">
                        <img style="margin-top: 20px; width: 50%; height: auto;" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/theme/plugins/images/'. $logo))) }}" alt="Logo" />
                        <br />
                        Nit {{ $nit }}
                    </td>
                    <td>
                        <p class="header">
                            REP&Uacute;BLICA DE COLOMBIA
                        </p>
                        <p class="header">
                            {{ strtoupper($alcaldia) }}
                        </p>
                        <p class="header">
                            TESORER&Iacute;A MUNICIPAL
                        </p>
                        <p class="header">
                            CERTIFICADO DE PAZ Y SALVO - IMPUESTO PREDIAL
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        <div id="body">
            <table class="titles" style="width: 100%; margin-top: 0px;">
                <tr>
                    <td style="width: 100%;">
                        <h3 class="title">EL SUSCRITO TESORERO(A)</h3>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3 class="title" style="padding-top: 20px; padding-bottom: 20px;">CERTIFICA</h3>
                    </td>
                </tr>
            </table>
            <table class="info-encabezado" style="width: 100%; margin-top: 15px;">
                <tr>
                    <th style="width: 10%;">Certificado No:</th>
                    <th style="width: 20%;">{{ $numero_certificado }}</th>
                    <th style="width: 10%;">Cod. postal:</th>
                    <th style="width: 20%;">{{ $codigo_postal }}</th>
                </tr>
            </table>
            <table class="info-encabezado" style="width: 100%; margin-top: 10px;">
                <tr>
                    <td>
                        <p>
                            Que en los archivos de la tesorer&iacute;a municipal aparece inscrito el predio con c&oacute;digo catastral n&uacute;mero: <b>{{ $predio->codigo_predio }}</b> el cual figura a nombre de <b>{{ $propietario_ppal->nombre }}</b>, documento de identidad n&uacute;mero <b>{{ $propietario_ppal->identificacion }}</b> con las siguientes especificaciones.
                            <br /><b>&Uacute;ltimo pago con factura No.:</b> {{ $ultimo_anio_pagado->factura_pago }}
                            <br /><b>Fecha:</b> {{ substr($ultimo_anio_pagado->fecha_pago, 0, 10) }}
                        </p>
                    </td>
                </tr>
            </table>
            <table class="info-predio" style="width: 100%; margin-top: 10px;">
                <tr>
                    <th rowspan="2" style="width: 50%;">DIRECCI&Oacute;N DEL PREDIO</th>
                    <th rowspan="2" style="width: 8%;">UBICACI&Oacute;N</th>
                    <th colspan="3" style="width: 15%;">&Aacute;REA</th>
                    <th rowspan="2" style="width: 15%;">VALOR AVALUO</th>
                    <th rowspan="2" style="width: 5%;">A&Ntilde;O AVALUO</th>
                </tr>
                <tr>
                    <th>HA</th>
                    <th>M2</th>
                    <th>CONS</th>
                </tr>
                <tr>
                    <td class="text-left">{{ $predio->direccion }}</td>
                    <td class="text-center">{{ $predio->zonas_descripcion }}</td>
                    <td class="text-center">{{ $predio->area_hectareas == '.00' ? 0 : str_replace('.00', '', $predio->area_hectareas) }}</td>
                    <td class="text-center">{{ $predio->area_metros == '.00' ? 0 : str_replace('.00', '', $predio->area_metros) }}</td>
                    <td class="text-center">{{ $predio->area_construida == '.00' ? 0 : str_replace('.00', '', $predio->area_construida) }}</td>
                    <td class="text-center">@money($ultimo_anio_pagado->avaluo)</td>
                    <td class="text-center">{{ $ultimo_anio_pagado->ultimo_anio }}</td>
                </tr>
            </table>
            <div class="info" style="padding-top: 25px; padding-bottom: 15px; width: 100%;">
                El cual est&aacute; registrado con los siguientes propietarios:
            </div>
            <table class="info-resumen" style="width: 100%; margin-top: 5px;">
                <tr>
                    <th class="text-left" style="width: 5%;">No.</th>
                    <th class="text-left" style="width: 10%;">C&eacute;dula / NIT</th>
                    <th class="text-left">Nombre</th>
                </tr>
                @php($cont_propietarios = 0)
                @foreach($propietarios as $propietario)
                    @if($cont_propietarios < 5)
                        <tr>
                            <td class="text-left">{{ strlen($propietario->jerarquia) < 3 ? str_pad(trim($propietario->jerarquia), 3, "0", STR_PAD_LEFT) : $propietario->jerarquia }}</td>
                            <td class="text-left">{{ $propietario->identificacion }}</td>
                            <td class="text-left">{{ $propietario->nombre }}</td>
                        </tr>
                    @elseif ($cont_propietarios == 5)
                        <tr>
                            <td class="text-left" colspan="3">
                                <p>
                                    La lista de propietarios se extiende m&aacute;s all&aacute; de la cantidad permitida para mostrar en este documento. Por defecto se muestran los 5 primeros.<br /><b>N&uacute;mero total de propietarios: {{ count($propietarios) }}</b>.
                                </p>
                            </td>
                        </tr>
                    @else
                        @break;
                    @endif
                    @php($cont_propietarios += 1)
                @endforeach
            </table>
            <div class="info" style="padding-top: 25px; width: 100%;">
                El cual se encuentra a PAZ Y SALVO por concepto de impuesto predial.
            </div>
            <div class="info" style="padding-top: 10px; width: 100%;">
                Expedido el {{ $fecha_expedicion }}
            </div>
            <div class="info" style="padding-top: 10px; width: 100%;">
                Se expide con destino a: <b>{{ $destino }}</b>
            </div>
            <div class="info" style="padding-top: 15px; width: 100%;">
                <p>
                    Actualmente el municipio de La Capilla no tiene adoptada la contribuci&oacute;n de valorizaci&oacute;n para predios ubicados dentro de su jurisdicci&oacute;n.
                </p>
                <p>
                    La presente certificaci&oacute;n se expide en relaci&oacute;n con el predio identificado con c&eacute;dula catastral No. <b>{{ $predio->codigo_predio }}</b>
                </p>
            </div>
            <table class="info-validez" style="width: 100%; margin-top: 50px;">
                <tr>
                    <td style="width: 50%;">
                        V&aacute;lido hasta <b>{{ $fecha_validez }}</b>
                    </td>
                    <td style="text-align: center;">
                        {{-- <img style="position: absolute; top: -30px; width: 20%; height: auto; margin-left: 100px;" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/theme/plugins/images/firma-paz-y-salvo-paipa.png'))) }}" alt="Firma" /> --}}
                        <hr style="margin: 0 auto; border: 1px solid #302f2f; width: 80%;">
                    </td>
                </tr>
                <tr>
                    <td>Valor <b>{{ $valor }}</b> Pesos m/cte.</td>
                    <td style="text-align: center;">TESORERO(A) MUNICIPAL</td>
                </tr>
                {{-- <tr>
                    <td style="padding-top: 20px;" colspan="2">NO SE COBRA IMPUESTO POR VALORIZACI&Oacute;N.</td>
                </tr> --}}
                <tr>
                    <td class="text-small" colspan="2">P&aacute;gina 1 de 1</td>
                </tr>
                <tr>
                    <td class="text-small" colspan="2">Elabor&oacute;: <b>{{ $usuario }}</b></td>
                </tr>
            </table>
        </div>
        <div id="footer">
            TESORER&Iacute;A MUNICIPAL
        </div>
    </div>
</body>
</html>
