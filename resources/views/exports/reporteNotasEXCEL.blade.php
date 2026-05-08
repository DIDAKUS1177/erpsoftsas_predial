<table style="width: 100%;">
    <thead>
        <tr style="border: 1px solid #000;">
            <td style="border: 1px solid #000; width: 20%; padding: 10px;">
                <img src="{{ public_path('/theme/plugins/images/'.$logo) }}" width="auto" height="120px" alt="logo" class="dark-logo" style="margin: 10px;" />
            </td>
            <td style="border: 1px solid #000; width: 88%; text-align: center; vertical-align: middle;" colspan="9">
                <h4><b>{{ strtoupper($alcaldia) }}</b></h4>
                <h4><b>NIT {{ $nit }}</b></h4>
                <h4><b>IMPUESTO PREDIAL UNIFICADO</b></h4>
                <h4><b>REPORTE DE NOTAS DEBITO-CREDITO A FACTURAS</b></h4>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr><td colspan="27" height="25"></td></tr>

        <tr>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>C&Oacute;DIGO PREDIO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>FACTURA</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>A&Ntilde;O</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>USUARIO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>FECHA CREACI&Oacute;N</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PREV. IMPUESTO PREDIAL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PREV. INTERESES PREDIAL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PREV. IMPUESTO CAR</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PREV. INTERESES CAR</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PREV. DESCUENTO PREDIAL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PREV. SOBRETASA PREDIAL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PREV. DESCUENTO CAR</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PREV. SOBRETASA BOMBERIL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PREV. SALDO A FAVOR / DEV</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PREV. ALUMBRADO P&Uacute;BLICO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PREV. TOTAL FACTURA</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>IMPUESTO PREDIAL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>INTERESES PREDIAL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>IMPUESTO CAR</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>INTERESES CAR</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>DESCUENTO PREDIAL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>SOBRETASA PREDIAL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>DESCUENTO CAR</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>SOBRETASA BOMBERIL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>SALDO A FAVOR / DEV</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>ALUMBRADO P&Uacute;BLICO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>TOTAL FACTURA</b></th>
        </tr>
        @if (count($registros) > 0)
        @php($suma_prev_valor_concepto1 = 0)
        @php($suma_prev_valor_concepto2 = 0)
        @php($suma_prev_valor_concepto3 = 0)
        @php($suma_prev_valor_concepto4 = 0)
        @php($suma_prev_valor_concepto13 = 0)
        @php($suma_prev_valor_concepto14 = 0)
        @php($suma_prev_valor_concepto15 = 0)
        @php($suma_prev_valor_concepto16 = 0)
        @php($suma_prev_valor_concepto17 = 0)
        @php($suma_prev_valor_concepto18 = 0)
        @php($suma_prev_total_calculo = 0)
        @php($suma_valor_concepto1 = 0)
        @php($suma_valor_concepto2 = 0)
        @php($suma_valor_concepto3 = 0)
        @php($suma_valor_concepto4 = 0)
        @php($suma_valor_concepto13 = 0)
        @php($suma_valor_concepto14 = 0)
        @php($suma_valor_concepto15 = 0)
        @php($suma_valor_concepto16 = 0)
        @php($suma_valor_concepto17 = 0)
        @php($suma_valor_concepto18 = 0)
        @php($suma_total_calculo = 0)
        @foreach($registros as $nota)
            <tr>
                <td style="text-align: center; border: 1px solid #000;" width="30">{{ $nota->codigo_predio }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="13">{{ $nota->factura_pago }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="8">{{ $nota->anio }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="35">{{ $nota->nombres }} {{ $nota->apellidos }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="23">{{ $nota->created_at }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto1 != $nota->valor_concepto1 ? '#f1d7d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->prev_valor_concepto1 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto2 != $nota->valor_concepto2 ? '#f1d7d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->prev_valor_concepto2 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto3 != $nota->valor_concepto3 ? '#f1d7d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->prev_valor_concepto3 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto4 != $nota->valor_concepto4 ? '#f1d7d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->prev_valor_concepto4 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto13 != $nota->valor_concepto13 ? '#f1d7d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->prev_valor_concepto13 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto14 != $nota->valor_concepto14 ? '#f1d7d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->prev_valor_concepto14 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto15 != $nota->valor_concepto15 ? '#f1d7d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->prev_valor_concepto15 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto16 != $nota->valor_concepto16 ? '#f1d7d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->prev_valor_concepto16 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto17 != $nota->valor_concepto17 ? '#f1d7d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->prev_valor_concepto17 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto18 != $nota->valor_concepto18 ? '#f1d7d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->prev_valor_concepto18 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_total_calculo != $nota->total_calculo ? '#f1d7d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->prev_total_calculo }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto1 != $nota->valor_concepto1 ? '#e8f1d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->valor_concepto1 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto2 != $nota->valor_concepto2 ? '#e8f1d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->valor_concepto2 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto3 != $nota->valor_concepto3 ? '#e8f1d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->valor_concepto3 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto4 != $nota->valor_concepto4 ? '#e8f1d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->valor_concepto4 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto13 != $nota->valor_concepto13 ? '#e8f1d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->valor_concepto13 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto14 != $nota->valor_concepto14 ? '#e8f1d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->valor_concepto14 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto15 != $nota->valor_concepto15 ? '#e8f1d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->valor_concepto15 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto16 != $nota->valor_concepto16 ? '#e8f1d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->valor_concepto16 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto17 != $nota->valor_concepto17 ? '#e8f1d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->valor_concepto17 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_valor_concepto18 != $nota->valor_concepto18 ? '#e8f1d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->valor_concepto18 }}</td>
                <td style="text-align: right; border: 1px solid #000; background-color: {{ $nota->prev_total_calculo != $nota->total_calculo ? '#e8f1d7' : '#ffffff' }}" width="27" data-format="$#,##0_-">{{ $nota->total_calculo }}</td>
            </tr>
            @php($suma_prev_valor_concepto1 += $nota->prev_valor_concepto1)
            @php($suma_prev_valor_concepto2 += $nota->prev_valor_concepto2)
            @php($suma_prev_valor_concepto3 += $nota->prev_valor_concepto3)
            @php($suma_prev_valor_concepto4 += $nota->prev_valor_concepto4)
            @php($suma_prev_valor_concepto13 += $nota->prev_valor_concepto13)
            @php($suma_prev_valor_concepto14 += $nota->prev_valor_concepto14)
            @php($suma_prev_valor_concepto15 += $nota->prev_valor_concepto15)
            @php($suma_prev_valor_concepto16 += $nota->prev_valor_concepto16)
            @php($suma_prev_valor_concepto17 += $nota->prev_valor_concepto17)
            @php($suma_prev_valor_concepto18 += $nota->prev_valor_concepto18)
            @php($suma_prev_total_calculo += $nota->prev_total_calculo)
            @php($suma_valor_concepto1 += $nota->valor_concepto1)
            @php($suma_valor_concepto2 += $nota->valor_concepto2)
            @php($suma_valor_concepto3 += $nota->valor_concepto3)
            @php($suma_valor_concepto4 += $nota->valor_concepto4)
            @php($suma_valor_concepto13 += $nota->valor_concepto13)
            @php($suma_valor_concepto14 += $nota->valor_concepto14)
            @php($suma_valor_concepto15 += $nota->valor_concepto15)
            @php($suma_valor_concepto16 += $nota->valor_concepto16)
            @php($suma_valor_concepto17 += $nota->valor_concepto17)
            @php($suma_valor_concepto18 += $nota->valor_concepto18)
            @php($suma_total_calculo += $nota->total_calculo)
        @endforeach
        <tr>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" colspan="5"><b>TOTALES</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_prev_valor_concepto1 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_prev_valor_concepto2 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_prev_valor_concepto3 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_prev_valor_concepto4 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_prev_valor_concepto13 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_prev_valor_concepto14 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_prev_valor_concepto15 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_prev_valor_concepto16 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_prev_valor_concepto17 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_prev_valor_concepto18 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_prev_total_calculo }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_valor_concepto1 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_valor_concepto2 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_valor_concepto3 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_valor_concepto4 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_valor_concepto13 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_valor_concepto14 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_valor_concepto15 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_valor_concepto16 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_valor_concepto17 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_valor_concepto18 }}</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_total_calculo }}</b></th>
        </tr>
        @else
        <tr>
            <td colspan="27" style="text-align: center; font-size: 110%;">NO HAY INFORMACI&Oacute;N DISPONIBLE</td>
        </tr>
        @endif
    </tbody>
</table>
