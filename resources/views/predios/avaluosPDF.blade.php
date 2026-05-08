<!DOCTYPE html>
<html>
<head>
    <title>Avaluo predial</title>
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
                        <p class="header">
                            REP&Uacute;BLICA DE COLOMBIA</p>
                        <p class="header">{{ strtoupper($alcaldia) }}</p>
                        <p class="header">
                            NIT {{ $nit }}</p>
                        <p class="header">
                            HISTORICO DE PAGOS</p>
                    </td>
                </tr>
            </table>
        </div>
        <div id="body">
            <table class="info-encabezado" style="width: 100%; margin-top: 15px;">
                <tr>
                    <th style="width: 20%;">C&eacute;dula catastral:</th>
                    <td style="width: 80%;">{{ $predio->codigo_predio }}</td>
                </tr>
                <tr>
                    <th style="width: 20%;">Propietario:</th>
                    <td style="width: 80%;">{{ $propietario_ppal->nombre}}</td>
                </tr>
                <tr>
                    <th style="width: 20%;">Direcci&oacute;n:</th>
                    <td style="width: 80%;">{{ $predio->direccion }}</td>
                </tr>
            </table>
            <table class="info-predio" style="width: 100%; margin-top: 10px;">
                <tr>
                    <th style="width: auto;">RECIBO</th>
                    <th style="width: auto;">FECHA</th>
                    <th style="width: auto;">BANCO</th>
                    <th style="width: auto;">VALOR</th>
                    <th style="width: auto;">A&Ntilde;O</th>
                    <th style="width: auto;">AVALUO</th>
                    <th style="width: auto;">TIPO RECIBO</th>
                    <th style="width: auto;">PRESCRITO</th>
                    <th style="width: auto;">EXENTO</th>
                </tr>
                @php($suma_impuesto = 0)
                @foreach($avaluos as $avaluo)
                <tr>
                    <td class="text-center">{{ $avaluo->factura_pago }}</td>
                    <td class="text-center">{{ $avaluo->fecha_pago }}</td>
                    <td class="text-center">{{ $avaluo->banco }}</td>
                    <td class="text-right">@money($avaluo->valor_pago)</td>
                    <td class="text-center">{{ $avaluo->anio }}</td>
                    <td class="text-right">@money($avaluo->avaluo)</td>
                    <td class="text-center">Vigencia</td>
                    <td class="text-center">{{ $avaluo->prescrito }}</td>
                    <td class="text-center">{{ $avaluo->exencion }}</td>
                </tr>
                @php($suma_impuesto += $avaluo->valor_pago)
                @endforeach
                <tr class="totales">
                    <th class="text-right" colspan="3">TOTAL PAGO</th>
                    <th class="text-right">@money($suma_impuesto)</th>
                    <th colspan="5" style="background-color: #FFFFFF; border-right: 0px; border-bottom: 0px;"></th>
                </tr>
            </table>
        </div>
        <div id="footer">
            SECRETAR&Iacute;A DE HACIENDA
        </div>
    </div>
</body>
</html>
