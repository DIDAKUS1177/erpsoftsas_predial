<!DOCTYPE html>
<html>
<head>
    <title>Estado de cuenta predial</title>
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
            height:35px;   /* Height of the footer */
            text-align: center;
            font-size: 80%;
            border-top: 1px solid #c0c0c0;
        }

		/* div.page { width:100%; padding-top: 5%; padding-bottom: 100px; margin: 0 auto; } */
        table { border-collapse: collapse; font-size: 100%; width: 100%; }
        /* td { padding: 1px; } */
        /* th { padding: 1px; background-color: #f3efef; } */
		/* h3.title { width: 100%; text-align: center; } */

        table.table-header tr td { border: 0px; font-size: 100%; }
        p.header { width: 100%; text-align:center; font-weight: bold; padding: 0px; margin: 0px; }
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
                    <td style="width: 20%;">
                        <img style="width: 70%; height: auto;" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/theme/plugins/images/'. $logo))) }}" alt="Logo" />
                    </td>
                    <td>
                        <p class="header">{{ strtoupper($alcaldia) }}</p>
                        <p class="header">NIT {{ $nit }}</p>
                        <p class="header">IMPUESTO PREDIAL UNIFICADO</p>
                        <p class="header">ESTADO DE CUENTA A {{ $fecha }}</p>
                    </td>
                </tr>
            </table>
        </div>
        <div id="body">
            <table class="info-encabezado" style="width: 100%; margin-top: 15px;">
                <tr>
                    <th style="width: 20%;">Identificaci&oacute;n:</th>
                    <td style="width: 30%;">{{ $propietario_ppal->identificacion}}</td>
                    <th style="width: 20%;">Propietario:</th>
                    <td style="width: 30%;">{{ $propietario_ppal->nombre}}</td>
                </tr>
                <tr>
                    <th style="width: 20%;">C&eacute;dula catastral:</th>
                    <td style="width: 30%;">{{ $predio->codigo_predio }}</td>
                    <th style="width: 20%;">Direcci&oacute;n:</th>
                    <td style="width: 30%;">{{ $predio->direccion }}</td>
                </tr>
            </table>
            <table class="info-encabezado" style="width: 100%; margin-top: 0px;">
                <tr>
                    <th style="width: auto;">Aval&uacute;o actual:</th>
                    <td style="width: auto;">@money($predio_pago->avaluo)</td>
                    <th style="width: auto;">&Aacute;rea (M2):</th>
                    <td style="width: auto;">{{ number_format($predio->area_metros, 2) }}</td>
                    <th style="width: auto;">&Aacute;rea Const. (M2):</th>
                    <td style="width: auto;">{{ number_format($predio->area_construida, 2) }}</td>
                    <th style="width: auto;">Tarifa:</th>
                    <td style="width: auto;">{{ number_format($predio_pago->tarifa * 1000, 2) }}</td>
                </tr>
                <tr>
                    <th style="width: auto;">&Uacute;ltimo pago:</th>
                    <td style="width: auto;">{{ $predio_pago->ultimo_anio }}</td>
                    <td colspan="6"></td>
                </tr>
            </table>
            <table class="info-predio" style="width: 100%; margin-top: 10px;">
                <tr>
                    <th style="width: auto;">VIG.</th>
                    <th style="width: auto;">AVALUO</th>
                    <th style="width: auto;">IMPUESTO</th>
                    <th style="width: auto;">INTERESES</th>
                    <th style="width: auto;">CAR</th>
                    <th style="width: auto;">INTERES CAR</th>
                    <th style="width: auto;">DESCUENTO</th>
                    <th style="width: auto;">OTROS</th>
                    <th style="width: auto;">TOTAL</th>
                    <th style="width: auto;">AP</th>
                </tr>
                @php($suma_impuesto = 0)
                @php($suma_interes_impuesto = 0)
                @php($suma_car = 0)
                @php($suma_interes_car = 0)
                @php($suma_descuento = 0)
                @php($suma_otros = 0)
                @php($suma_total = 0)
                @foreach($pendientes as $pendiente)
                <tr>
                    <td class="text-center">{{ $pendiente->vigencia }}</td>
                    <td class="text-right">@money($pendiente->avaluo)</td>
                    <td class="text-right">@money($pendiente->impuesto)</td>
                    <td class="text-right">@money($pendiente->interes_impuesto)</td>
                    <td class="text-right">@money($pendiente->car)</td>
                    <td class="text-right">@money($pendiente->interes_car)</td>
                    <td class="text-right">@money($pendiente->descuento)</td>
                    <td class="text-right">@money($pendiente->otros)</td>
                    <td class="text-right">@money($pendiente->total)</td>
                    <td class="text-center">{{ $pendiente->acuerdo }}</td>
                </tr>
                @php($suma_impuesto += $pendiente->impuesto)
                @php($suma_interes_impuesto += $pendiente->interes_impuesto)
                @php($suma_car += $pendiente->car)
                @php($suma_interes_car += $pendiente->interes_car)
                @php($suma_descuento += $pendiente->descuento)
                @php($suma_otros += $pendiente->otros)
                @php($suma_total += $pendiente->total)
                @endforeach
                <tr class="totales">
                    <th class="text-right" colspan="2">TOTALES</th>
                    <th class="text-right">@money($suma_impuesto)</th>
                    <th class="text-right">@money($suma_interes_impuesto)</th>
                    <th class="text-right">@money($suma_car)</th>
                    <th class="text-right">@money($suma_interes_car)</th>
                    <th class="text-right">@money($suma_descuento)</th>
                    <th class="text-right">@money($suma_otros)</th>
                    <th class="text-right">@money($suma_total)</th>
                    <th class="text-right" style="background-color: #FFFFFF; border-right: 0px; border-bottom: 0px;"></th>
                </tr>
            </table>
        </div>
        <div id="footer">
            TESORER&Iacute;A MUNICIPAL
        </div>
    </div>
</body>
</html>
