<table style="width: 100%;">
    <thead>
        <tr style="border: 1px solid #000;">
            <td style="border: 1px solid #000; width: 20%; padding: 10px;" colspan="1">
                <img src="{{ public_path('/theme/plugins/images/'.$logo) }}" width="auto" height="120px" alt="logo" class="dark-logo" style="margin: 10px;" />
            </td>
            <td style="border: 1px solid #000; width: 88%; text-align: center; vertical-align: middle;" colspan="9">
                <h4><b>{{ strtoupper($alcaldia) }}</b></h4>
                <h4><b>NIT {{ $nit }}</b></h4>
                <h4><b>IMPUESTO PREDIAL UNIFICADO</b></h4>
                <h4><b>ESTADO DE CUENTA A {{ $fecha }}</b></h4>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr><td colspan="10" height="25"></td></tr>
        <tr>
            <th style="background-color: #ededed; text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="1" height="25"><b>Identificaci&oacute;n:</b></th>
            <td style="text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="4" height="25">{{ $propietario_ppal->identificacion }}</td>
            <th style="background-color: #ededed; text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="1" height="25"><b>Propietario:</b></th>
            <td style="text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="4" height="25">{{ $propietario_ppal->nombre }}</td>
        </tr>
        <tr>
            <th style="background-color: #ededed; text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="1" height="25"><b>C&eacute;dula catastral:</b></th>
            <td style="text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="4" height="25">{{ $predio->codigo_predio }}</td>
            <th style="background-color: #ededed; text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="1" height="25"><b>Direcci&oacute;n:</b></th>
            <td style="text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="4" height="25">{{ $predio->direccion }}</td>
        </tr>
        <tr><td colspan="10" height="25"></td></tr>

        <tr>
            <th style="background-color: #ededed; width: auto; text-align: left; vertical-align: middle; border: 1px solid #000;" height="25"><b>Aval&uacute;o actual:</b></th>
            <td data-format="$#,##0_-" style="width: auto; text-align: left; vertical-align: middle; border: 1px solid #000;" height="25">{{ $predio_pago->avaluo }}</td>
            <th style="background-color: #ededed; width: auto; text-align: left; vertical-align: middle; border: 1px solid #000;" height="25"><b>&Aacute;rea (M2):</b></th>
            <td style="width: auto; text-align: left; vertical-align: middle; border: 1px solid #000;" height="25">{{ number_format($predio->area_metros, 2) }}</td>
            <th style="background-color: #ededed; width: auto; text-align: left; vertical-align: middle; border: 1px solid #000;" height="25"><b>&Aacute;rea Const. (M2):</b></th>
            <td style="width: auto; text-align: left; vertical-align: middle; border: 1px solid #000;" height="25">{{ number_format($predio->area_construida, 2) }}</td>
            <th style="background-color: #ededed; width: auto; text-align: left; vertical-align: middle; border: 1px solid #000;" height="25"><b>Tarifa:</b></th>
            <td style="width: auto; text-align: left; vertical-align: middle; border: 1px solid #000;" height="25">{{ number_format($predio_pago->tarifa * 1000, 2) }}</td>
            <th style="background-color: #ededed; width: auto; text-align: left; vertical-align: middle; border: 1px solid #000;" height="25"><b>&Uacute;ltimo pago:</b></th>
            <td style="width: auto; text-align: left; vertical-align: middle; border: 1px solid #000;" height="25">{{ $predio_pago->ultimo_anio }}</td>
        </tr>

        <tr><td colspan="10" height="25"></td></tr>

        <tr>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>VIG.</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>AVALUO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>IMPUESTO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>INTERES</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>CAR</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>INTERES CAR</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>DESCUENTO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>OTROS</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>TOTAL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>AP</b></th>
        </tr>
        @if (count($registros) > 0)
        @php($suma_impuesto = 0)
        @php($suma_interes_impuesto = 0)
        @php($suma_car = 0)
        @php($suma_interes_car = 0)
        @php($suma_descuento = 0)
        @php($suma_otros = 0)
        @php($suma_total = 0)
        @foreach($registros as $estado_cuenta)
            <tr>
                <td style="text-align: center; border: 1px solid #000;" width="20">{{ $estado_cuenta->vigencia }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="27" data-format="$#,##0_-">{{ $estado_cuenta->avaluo }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="15" data-format="$#,##0_-">{{ $estado_cuenta->impuesto }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="15" data-format="$#,##0_-">{{ $estado_cuenta->interes_impuesto }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="20" data-format="$#,##0_-">{{ $estado_cuenta->car }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="20" data-format="$#,##0_-">{{ $estado_cuenta->interes_car }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="20" data-format="$#,##0_-">{{ $estado_cuenta->descuento }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="20" data-format="$#,##0_-">{{ $estado_cuenta->otros }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="20" data-format="$#,##0_-">{{ $estado_cuenta->total }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="20">{{ $estado_cuenta->acuerdo }}</td>
            </tr>
            @php($suma_impuesto += $estado_cuenta->impuesto)
            @php($suma_interes_impuesto += $estado_cuenta->interes_impuesto)
            @php($suma_car += $estado_cuenta->car)
            @php($suma_interes_car += $estado_cuenta->interes_car)
            @php($suma_descuento += $estado_cuenta->descuento)
            @php($suma_otros += $estado_cuenta->otros)
            @php($suma_total += $estado_cuenta->total)
        @endforeach
        <tr>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" colspan="2"><b>TOTALES</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_impuesto }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_interes_impuesto }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_car }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_interes_car }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_descuento }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_otros }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_total }}</b></th>
            <th style="background-color: #ffffff; text-align: right; vertical-align: middle;"></th>
        </tr>
        @else
        <tr>
            <td colspan="10" style="text-align: center; font-size: 110%;">NO HAY INFORMACI&Oacute;N DISPONIBLE</td>
        </tr>
        @endif
    </tbody>
</table>
