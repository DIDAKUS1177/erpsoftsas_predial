<!DOCTYPE html>
<html>
<head>
    <title>Prescripciones predial</title>
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

        table.table-header tr td { border: 0px; font-size: 70%; }
        p.header { width: 100%; text-align:center; font-weight: bold; padding: 0px; margin: 0px; }
		table.titles tr td { border: 0px; font-size: 80%; text-align: center; }

		table.info-encabezado tr th { text-align:justify; font-size: 80%; }
		table.info-encabezado tr td { text-align:justify; font-size: 70%; }

        table.info-predio tr th { font-size: 50%; border: 1px solid #000; padding: 1px; background-color: #f3efef; }
		table.info-predio tr td { font-size: 50%; border: 1px solid #000; padding: 1px; }
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
                        <img style="width: 40%; height: auto;" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/theme/plugins/images/'. $logo))) }}" alt="Logo" />
                    </td>
                    <td>
                        <p class="header">REP&Uacute;BLICA DE COLOMBIA</p>
                        <p class="header">{{ strtoupper($alcaldia) }}</p>
                        <p class="header">NIT {{ $nit }}</p>
                        <p class="header">LISTA DE PRESCRIPCIONES</p>
                    </td>
                </tr>
            </table>
        </div>
        <div id="body">
            <table class="info-predio" style="width: 100%; margin-top: 10px;">
                <tr>
                    <th style="width: auto;">FECHA<br />PRESCRIPCION</th>
                    <th style="width: auto;">CODIGO<br />PREDIO</th>
                    <th style="width: auto;">VIGENCIA</th>
                    <th style="width: auto;">PREDIAL</th>
                    <th style="width: auto;">INTERES<br />PREDIAL</th>
                    <th style="width: auto;">CAR</th>
                    <th style="width: auto;">INTERES<br />CAR</th>
                    <th style="width: auto;">DESCUENTO<br />PREDIAL</th>
                    <th style="width: auto;">DESCUENTO<br />CAR</th>
                    <th style="width: auto;">TASA<br />BOMBERIL</th>
                    <th style="width: auto;">ALUMBRADO</th>
                </tr>
                @if (count($prescripciones) > 0)
                @php($suma_valor_concepto1 = 0)
                @php($suma_valor_concepto2 = 0)
                @php($suma_valor_concepto3 = 0)
                @php($suma_valor_concepto4 = 0)
                @php($suma_valor_concepto13 = 0)
                @php($suma_valor_concepto15 = 0)
                @php($suma_valor_concepto16 = 0)
                @php($suma_valor_concepto18 = 0)
                @foreach($prescripciones as $prescribe)
                <tr>
                    <td class="text-center">{{ substr($prescribe->created_at, 0, 10) }}</td>
                    <td class="text-center">{{ $prescribe->codigo_predio }}</td>
                    <td class="text-center">{{ $prescribe->prescribe_anio }}</td>
                    <td class="text-right">@money($prescribe->valor_concepto1)</td>
                    <td class="text-right">@money($prescribe->valor_concepto2)</td>
                    <td class="text-right">@money($prescribe->valor_concepto3)</td>
                    <td class="text-right">@money($prescribe->valor_concepto4)</td>
                    <td class="text-right">@money($prescribe->valor_concepto13)</td>
                    <td class="text-right">@money($prescribe->valor_concepto15)</td>
                    <td class="text-right">@money($prescribe->valor_concepto16)</td>
                    <td class="text-right">@money($prescribe->valor_concepto18)</td>
                </tr>
                @php($suma_valor_concepto1 += $prescribe->valor_concepto1)
                @php($suma_valor_concepto2 += $prescribe->valor_concepto2)
                @php($suma_valor_concepto3 += $prescribe->valor_concepto3)
                @php($suma_valor_concepto4 += $prescribe->valor_concepto4)
                @php($suma_valor_concepto13 += $prescribe->valor_concepto13)
                @php($suma_valor_concepto15 += $prescribe->valor_concepto15)
                @php($suma_valor_concepto16 += $prescribe->valor_concepto16)
                @php($suma_valor_concepto18 += $prescribe->valor_concepto18)
                @endforeach
                <tr class="totales">
                    <th class="text-right" colspan="3">TOTAL PAGO</th>
                    <th class="text-right">@money($suma_valor_concepto1)</th>
                    <th class="text-right">@money($suma_valor_concepto2)</th>
                    <th class="text-right">@money($suma_valor_concepto3)</th>
                    <th class="text-right">@money($suma_valor_concepto4)</th>
                    <th class="text-right">@money($suma_valor_concepto13)</th>
                    <th class="text-right">@money($suma_valor_concepto15)</th>
                    <th class="text-right">@money($suma_valor_concepto16)</th>
                    <th class="text-right">@money($suma_valor_concepto18)</th>
                </tr>
                @else
                <tr>
                    <td colspan="11" class="text-center" style="font-size: 110%;">NO HAY INFORMACI&Oacute;N DISPONIBLE</td>
                </tr>
                @endif
            </table>
        </div>
        <div id="footer">
            SECRETAR&Iacute;A DE HACIENDA
        </div>
    </div>
</body>
</html>
