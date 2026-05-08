<!DOCTYPE html>
<html>
<head>
    <title>Factura predial</title>
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
            padding:2px;
        }
        #body {
            padding:2px;
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
        th, td { border: 1px solid #000000; }
        table { border-collapse: collapse; font-size: 80%; width: 100%; }
        td { padding: 1px; }
        th { padding: 1px; background-color: #f3efef; }
		p.header { width: 100%; text-align:center; font-weight: bold; padding: 0px; margin: 0px; }
        p.previa { color: tomato; width: 100%; text-align:center; font-weight: bold; padding: 0px; margin: 0px; }
		h3.title { width: 100%; text-align:right; }

        table.table-header tr td { border: 0px; }
		table.titles tr td { border: 0px; font-size: 80%; }

		table.info-predio tr th { white-space: nowrap; font-size: 80%; }
		table.info-predio tr td { text-align:center; font-size: 80%; }

        table.info-pagos tr th { white-space: nowrap; font-size: 70%; }
		table.info-pagos tr td { text-align:right; font-size: 70%; }
		table.info-pagos tr.totales th { text-align:right; }

        table.info-resumen tr th { white-space: nowrap; font-size: 80%; padding: 0px; }
		table.info-resumen tr td { white-space: nowrap; font-size: 80%; padding: 0px; padding-left: 3px; }

		table.info-alcaldia tr td { border: 0px; text-align: center; font-size: 80%; }

		table.info-codigo-barras tr td { border: 0px; text-align: center; font-size: 80%; }

        .info { font-size: 70%; }
		.negrilla {font-weight: bold;}
		.marca-agua { color: #c0c0c0;}

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
                        <p class="header">
                            SECRETAR&Iacute;A DE HACIENDA</p>
                        <p class="header">
                            IMPUESTO PREDIAL UNIFICADO</p>
                        @if($temporal > 0)
                        <p class="previa">
                            VISTA PREVIA FACTURA DE COBRO</p>
                        @else
                        <p class="header">
                            FACTURA DE COBRO</p>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div id="body">
            <table class="titles" style="width: 100%; margin-top: 0px;">
                <tr>
                    @if($temporal > 0 || $facturaYaPagada)
                    <td style="width: 40%;" rowspan="2">
                        <h3 class="title" style="color: tomato; text-align: left; font-weight: normal;">
                            @if($temporal > 0)
                            Documento no v&aacute;lido para la ejecuci&oacute;n del pago de impuesto predial.
                            @elseif($facturaYaPagada && $informativa != '1')
                            PAGO DE FACTURA YA REGISTRADO.<br />El predio se encuentra a paz y salvo.
                            @endif
                        </h3>
                    </td>
                    <td style="width: 40%;">
                        <h3 class="title">Fecha emisi&oacute;n:</h3>
                    </td>
                    @else
                    <td style="width: 80%;">
                        <h3 class="title">Fecha emisi&oacute;n:</h3>
                    </td>
                    @endif
                    <td>
                        @if($fecha == 'INDEFINIDA')
                        {{ $fecha }}
                        @else
                        {{ $fecha }}, {{ $hora }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3 class="title">No. factura:</h3>
                    </td>
                    <td>
                        {{ $numero_factura }}
                    </td>
                </tr>
            </table>
            <table class="info-predio" style="width: 100%; margin-top: 10px;">
                <tr>
                    <th style="width: 15%;">C&oacute;digo catastral</th>
                    <th style="width: 19%;">C&oacute;digo catastral ant.</th>
                    <th style="width: 10%;">Nit. / C.C.</th>
                    <th style="width: 14%;">No. recibo ant.</th>
                    <th style="width: 14%;">&Aacute;rea Ha.</th>
                    <th style="width: 14%;">&Aacute;rea M2.</th>
                    <th style="width: 14%;">&Aacute;rea Cont.</th>
                </tr>
                <tr>
                    <td>{{ $predio->codigo_predio }}</td>
                    <td>{{ $predio->codigo_predio_anterior != null ? $predio->codigo_predio_anterior : '' }}</td>
                    <td>{{ $predio->identificaciones }}</td>
                    <td>{{ $ultimo_anio_pagado->factura_pago }}</td>
                    <td>{{ number_format($predio->area_hectareas, 2) }}</td>
                    <td>{{ number_format($predio->area_metros, 2) }}</td>
                    <td>{{ number_format($predio->area_construida, 2) }}</td>
                </tr>
                <tr>
                    <th colspan="3">Propietario</th>
                    <th colspan="4">Direcci&oacute;n</th>
                </tr>
                <tr>
                    <td colspan="3">{{ $predio->propietarios }}</td>
                    <td colspan="4">{{ $predio->direccion }}</td>
                </tr>
            </table>
            <table class="info-predio" style="width: 100%; margin-top: 0px;">
                <tr>
                    <th style="width: 20%;">A&ntilde;os a pagar</th>
                    <th style="width: 20%;">Aval&uacute;o</th>
                    <th style="width: 20%;">&Uacute;ltimo a&ntilde;o pago</th>
                    <th style="width: 20%;">Fecha pago</th>
                    <th style="width: 20%;">Valor pagado</th>
                </tr>
                <tr>
                    <td>{{ $predio->anios_a_pagar }}</td>
                    <td>@money($predio->avaluo)</td>
                    <td>{{ $predio->ultimo_anio_pago }}</td>
                    <td>{{ $ultimo_anio_pagado->fecha_pago }}</td>
                    <td>@money($ultimo_anio_pagado->valor_pago)</td>
                </tr>
            </table>
            <table class="info-pagos" style="width: 100%; margin-top: 10px;">
                <tr>
                    <th>A&Ntilde;O</th>
                    <th>%M<br />TAR</th>
                    <th>AVAL&Uacute;O</th>
                    @if(intval($unir_impuesto_car) == 1)
                    <th>IMPUESTO</th>
                    <th>INTER&Eacute;S</th>
                    @else
                    <th>IMP.<br />PREDIAL</th>
                    <th>INT.<br />PREDIAL</th>
                    <th>CAR</th>
                    <th>INT.<br />CAR</th>
                    @endif
                    <th>TASA<br />BOMBERIL</th>
                    <th>DESCUENTO<br />IMP</th>
                    <th>Otros</th>
                    <th>TOTAL</th>
                </tr>
                <!----------------------------->
                @if(count($lista_pagos) > 0)
                    @php($suma_impuesto = 0)
                    @php($suma_interes = 0)
                    @php($suma_impuesto_interes = 0)
                    @php($suma_car = 0)
                    @php($suma_car_interes = 0)
                    @php($suma_dieciseis = 0)
                    @php($suma_trece = 0)
                    @php($suma_dieciocho = 0)
                    @php($suma_total = 0)
                    @foreach($lista_pagos as $pago)
                    <tr>
                        <td>{{ $pago->anio }}</td>
                        <td>{{ $pago->m_tar }}</td>
                        <td>@money($pago->avaluo)</td>
                        @if(intval($unir_impuesto_car) == 1)
                        <td>@money($pago->impuesto + $pago->car)</td>
                        <td>@money($pago->impuesto_interes + $pago->car_interes)</td>
                        @else
                        <td>@money($pago->impuesto)</td>
                        <td>@money($pago->impuesto_interes)</td>
                        <td>@money($pago->car)</td>
                        <td>@money($pago->car_interes)</td>
                        @endif
                        <td>@money($pago->dieciocho)</td>
                        <td>@money($pago->trece)</td>
                        <td>@money($pago->dieciseis)</td>
                        <td>@money($pago->total)</td>
                    </tr>
                    @if(intval($unir_impuesto_car) == 1)
                        @php($suma_impuesto += ($pago->impuesto + $pago->car))
                        @php($suma_interes += ($pago->impuesto_interes + $pago->car_interes))
                    @else
                        @php($suma_impuesto += $pago->impuesto)
                        @php($suma_impuesto_interes += $pago->impuesto_interes)
                        @php($suma_car += $pago->car)
                        @php($suma_car_interes += $pago->car_interes)
                    @endif
                    @php($suma_dieciseis += $pago->dieciseis)
                    @php($suma_trece += $pago->trece)
                    @php($suma_dieciocho += $pago->dieciocho)
                    @php($suma_total += $pago->total)
                    @endforeach
                    <!----------------------------->
                    <tr class="totales">
                        <th colspan="3">TOTALES</th>
                        @if(intval($unir_impuesto_car) == 1)
                            <th>@money($suma_impuesto)</th>
                            <th>@money($suma_interes)</th>
                        @else
                            <th>@money($suma_impuesto)</th>
                            <th>@money($suma_impuesto_interes)</th>
                            <th>@money($suma_car)</th>
                            <th>@money($suma_car_interes)</th>
                        @endif
                        <th>@money($suma_dieciocho)</th>
                        <th>@money($suma_trece)</th>
                        <th>@money($suma_dieciseis)</th>
                        <th>@money($suma_total)</th>
                    </tr>
                @else
                <tr>
                    @if(intval($unir_impuesto_car) == 1)
                    <td colspan="9" style="text-align: center;">No hay informaci&oacute;n disponible</td>
                    @else
                    <td colspan="11" style="text-align: center;">No hay informaci&oacute;n disponible</td>
                    @endif
                </tr>
                @endif
            </table>
            <div class="negrilla info" style="padding-top: 10px; padding-bottom: 10px; width: 100%; text-align: center;">
                USUARIO
            </div>
            <div class="info" style="padding-bottom: 5px; width: 100%; text-align: center;">
                ESTA FACTURA PRESTA MERITO EJECUTIVO CONFORME AL ART. 828 DEL E.T.
            </div>
            @if(count($lista_pagos) > 0)
                <hr style="border: 1px dashed #000;
                    border-style: none none dashed;
                    color: #fff;
                    background-color: #fff;" />
                <table class="info-resumen" style="width: 100%; margin-top: 5px;">
                    <tr>
                        <th>Predio</th>
                        <td>{{ $predio->codigo_predio }}</th>
                        <th>A&ntilde;os a pagar</th>
                        <td>{{ $predio->anios_a_pagar }}</td>
                        <th>N&uacute;mero factura</th>
                        <td class="negrilla" style="font-size: 120%;">{{ $numero_factura }}</td>
                    </tr>
                    <tr>
                        <th>Propietario</th>
                        <td colspan="5">{{ $predio->propietarios }}</th>
                    </tr>
                    <tr>
                        <th>Direcci&oacute;n</th>
                        <td colspan="5">{{ $predio->direccion }}</th>
                    </tr>
                </table>
                <table class="info-alcaldia" style="width: 100%; margin-top: 10px;">
                    <tr>
                        <td>{{ strtoupper($alcaldia) }}</td>
                        <td>NIT {{ $nit }}</th>
                        <td>IMPUESTO PREDIAL UNIFICADO</td>
                    </tr>
                </table>
                @if(count($valores_factura) > 0 && !$facturaYaPagada)
                    <table class="info-codigo-barras" style="width: 100%; margin-top: 10px; padding-bottom: 10px;">
                        {{-- @for ($x = 0; $x < count($valores_factura); $x++) --}}
                        {{-- @for ($x = 0; $x < 1; $x++) --}}
                        {{-- @php($x = 0) --}} {{-- // primer codigo de barras --}}
                        @php($x = count($valores_factura) > 1 ? 1 : 0) {{-- segundo codigo de barras --}}
                        <tr>
                            <td style="width: 53%; padding-top: 15px; border: 0px solid #000; text-align: center;">
                                {{-- {!! DNS1D::getBarcodeHTML($barras[$x], 'C128', 1, 80) !!} --}}
                                <img style="padding-left: 5px; padding-top: 5px;" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barras[$x], 'C128') }}" height="77" width="371" />
                                <span style="width: 100%; font-size: 80%;">{{ $barras_texto[$x] }}</span>
                            </td>
                            <td style="width: 19%;">
                                <table style="width: 100%; font-size: 120%;">
                                    <tr><td class="negrilla">Pague hasta {{ $fechas_pago_hasta[$x] }}</td></tr>
                                    <tr><td>Descuento {{ intval($porcentajes_descuento[$x]) }}%</td></tr>
                                    <tr><td class="negrilla">@money($valores_factura[$x])</td></tr>
                                </table>
                            </td>
                            <td class="marca-agua">SELLO BANCO</td>
                        </tr>
                        {{-- @endfor --}}
                    </table>
                    <hr style="border: 1px dashed #000;
                        border-style: none none dashed;
                        color: #fff;
                        background-color: #fff;" />
                    {{-- Replica de encabezado BANCO --}}
                    <div class="negrilla info" style="padding-top: 10px; padding-bottom: 5px; width: 100%; text-align: center;">
                        - BANCO -
                    </div>
                    <table class="info-predio" style="width: 100%; margin-top: 0px; font-size: 65%;">
                        <tr>
                            <th style="width: 15%;">C&oacute;digo catastral</th>
                            <th style="width: 19%;">C&oacute;digo catastral ant.</th>
                            <th style="width: 10%;">Nit. / C.C.</th>
                            <th style="width: 14%;">No. recibo ant.</th>
                            <th style="width: 14%;">&Aacute;rea Ha.</th>
                            <th style="width: 14%;">&Aacute;rea M2.</th>
                            <th style="width: 14%;">&Aacute;rea Cont.</th>
                        </tr>
                        <tr>
                            <td>{{ $predio->codigo_predio }}</td>
                            <td>{{ $predio->codigo_predio_anterior != null ? $predio->codigo_predio_anterior : '' }}</td>
                            <td>{{ $predio->identificaciones }}</td>
                            <td>{{ $ultimo_anio_pagado->factura_pago }}</td>
                            <td>{{ number_format($predio->area_hectareas, 2) }}</td>
                            <td>{{ number_format($predio->area_metros, 2) }}</td>
                            <td>{{ number_format($predio->area_construida, 2) }}</td>
                        </tr>
                        <tr>
                            <th colspan="3">Propietario</th>
                            <th colspan="4">Direcci&oacute;n</th>
                        </tr>
                        <tr>
                            <td colspan="3">{{ $predio->propietarios }}</td>
                            <td colspan="4">{{ $predio->direccion }}</td>
                        </tr>
                    </table>
                    <table class="info-predio" style="width: 100%; margin-top: 0px; font-size: 65%;">
                        <tr>
                            <th style="width: 20%;">A&ntilde;os a pagar</th>
                            <th style="width: 20%;">Aval&uacute;o</th>
                            <th style="width: 20%;">&Uacute;ltimo a&ntilde;o pago</th>
                            <th style="width: 20%;">Fecha pago</th>
                            <th style="width: 20%;">Valor pagado</th>
                        </tr>
                        <tr>
                            <td>{{ $predio->anios_a_pagar }}</td>
                            <td>@money($predio->avaluo)</td>
                            <td>{{ $predio->ultimo_anio_pago }}</td>
                            <td>{{ $ultimo_anio_pagado->fecha_pago }}</td>
                            <td>@money($ultimo_anio_pagado->valor_pago)</td>
                        </tr>
                    </table>
                    <table class="info-predio" style="width: 100%; margin-top: 5px; font-size: 75%; padding-bottom: 10px;">
                        <tr>
                            <th style="width: 10%;">No. factura:</th>
                            <th style="width: 23%;">{{ $numero_factura }}</th>
                            <th style="width: 10%;">Fecha emisi&oacute;n:</th>
                            <th style="width: 23%;">
                                @if($fecha == 'INDEFINIDA')
                                {{ $fecha }}
                                @else
                                {{ $fecha }}, {{ $hora }}
                                @endif
                            </th>
                            <th style="width: 11%;">Valor a pagar:</th>
                            <th style="width: 23%;">@money($valores_factura[0])</th>
                        </tr>
                    </table>
                    {{-- Replica de encabezado ALCALDIA --}}
                    <hr style="border: 1px dashed #000;
                        border-style: none none dashed;
                        color: #fff;
                        background-color: #fff;" />
                    <div class="negrilla info" style="padding-top: 10px; padding-bottom: 5px; width: 100%; text-align: center;">
                        - ALCALD&Iacute;A -
                    </div>
                    <table class="info-predio" style="width: 100%; margin-top: 0px; font-size: 65%;">
                        <tr>
                            <th style="width: 15%;">C&oacute;digo catastral</th>
                            <th style="width: 19%;">C&oacute;digo catastral ant.</th>
                            <th style="width: 10%;">Nit. / C.C.</th>
                            <th style="width: 14%;">No. recibo ant.</th>
                            <th style="width: 14%;">&Aacute;rea Ha.</th>
                            <th style="width: 14%;">&Aacute;rea M2.</th>
                            <th style="width: 14%;">&Aacute;rea Cont.</th>
                        </tr>
                        <tr>
                            <td>{{ $predio->codigo_predio }}</td>
                            <td>{{ $predio->codigo_predio_anterior != null ? $predio->codigo_predio_anterior : '' }}</td>
                            <td>{{ $predio->identificaciones }}</td>
                            <td>{{ $ultimo_anio_pagado->factura_pago }}</td>
                            <td>{{ number_format($predio->area_hectareas, 2) }}</td>
                            <td>{{ number_format($predio->area_metros, 2) }}</td>
                            <td>{{ number_format($predio->area_construida, 2) }}</td>
                        </tr>
                        <tr>
                            <th colspan="3">Propietario</th>
                            <th colspan="4">Direcci&oacute;n</th>
                        </tr>
                        <tr>
                            <td colspan="3">{{ $predio->propietarios }}</td>
                            <td colspan="4">{{ $predio->direccion }}</td>
                        </tr>
                    </table>
                    <table class="info-predio" style="width: 100%; margin-top: 0px; font-size: 65%;">
                        <tr>
                            <th style="width: 20%;">A&ntilde;os a pagar</th>
                            <th style="width: 20%;">Aval&uacute;o</th>
                            <th style="width: 20%;">&Uacute;ltimo a&ntilde;o pago</th>
                            <th style="width: 20%;">Fecha pago</th>
                            <th style="width: 20%;">Valor pagado</th>
                        </tr>
                        <tr>
                            <td>{{ $predio->anios_a_pagar }}</td>
                            <td>@money($predio->avaluo)</td>
                            <td>{{ $predio->ultimo_anio_pago }}</td>
                            <td>{{ $ultimo_anio_pagado->fecha_pago }}</td>
                            <td>@money($ultimo_anio_pagado->valor_pago)</td>
                        </tr>
                    </table>
                    <table class="info-predio" style="width: 100%; margin-top: 5px; font-size: 75%;">
                        <tr>
                            <th style="width: 10%;">No. factura:</th>
                            <th style="width: 23%;">{{ $numero_factura }}</th>
                            <th style="width: 10%;">Fecha emisi&oacute;n:</th>
                            <th style="width: 23%;">
                                @if($fecha == 'INDEFINIDA')
                                {{ $fecha }}
                                @else
                                {{ $fecha }}, {{ $hora }}
                                @endif
                            </th>
                            <th style="width: 11%;">Valor a pagar:</th>
                            <th style="width: 23%;">@money($valores_factura[0])</th>
                        </tr>
                    </table>
                @endif
            @else
                <table class="info-resumen" style="width: 100%; margin-top: 5px;">
                    <tr>
                        <td style="text-align: center;">NO HAY INFORMACI&Oacute;N DISPONIBLE PARA REALIZAR PAGO</td>
                    </tr>
                </table>
            @endif
        </div>
        <div id="footer">
            <span style="display: block; font-size: 80%;">SECRETAR&Iacute;A DE HACIENDA</span>
            <span style="display: block; font-size: 70%;">Dise&ntilde;ado e impreso por SISTEMAS ERPSOFT SAS</span>
            @if($temporal > 0 || $facturaYaPagada)
                <span style="color: tomato; font-size: 70%; display: block;">
                    @if($temporal > 0)
                    VISTA PREVIA FACTURA DE COBRO
                    @elseif ($facturaYaPagada && $informativa != '1')
                    PAGO DE FACTURA YA REGISTRADO. El predio se encuentra a paz y salvo.
                    @endif
                </span>
            @endif
        </div>
    </div>
</body>
</html>
