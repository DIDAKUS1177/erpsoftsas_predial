<table style="width: 100%;">
    <thead>
        <tr style="border: 1px solid #000;">
            <td style="border: 1px solid #000; width: 20%; padding: 10px;" colspan="1">
                <img src="{{ public_path('/theme/plugins/images/'.$logo) }}" width="auto" height="120px" alt="logo" class="dark-logo" style="margin: 10px;" />
            </td>
            <td style="border: 1px solid #000; width: 88%; text-align: center; vertical-align: middle;" colspan="10">
                <h4><b>REP&Uacute;BLICA DE COLOMBIA</b></h4>
                <h4><b>{{ strtoupper($alcaldia) }}</b></h4>
                <h4><b>NIT {{ $nit }}</b></h4>
                <h4><b>LISTA DE PRESCRIPCIONES</b></h4>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align: center; vertical-align: middle;" colspan="17" height="25"><h3><b>ENTRE FECHAS {{ $fecha_inicial }} Y {{ $fecha_final }}</b></h3></td>
        </tr>
        <tr>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>FECHA PRESCRIPCI&Oacute;N</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>C&Oacute;DIGO PREDIO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>VIGENCIA</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PREDIAL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>INTERES PREDIAL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>CAR</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>INTERES CAR</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>DESCUENTO PREDIAL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>DESCUENTO CAR</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>TASA BOMBERIL</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>ALUMBRADO</b></th>
        </tr>
        @if (count($registros) > 0)
        @php($suma_valor_concepto1 = 0)
        @php($suma_valor_concepto2 = 0)
        @php($suma_valor_concepto3 = 0)
        @php($suma_valor_concepto4 = 0)
        @php($suma_valor_concepto13 = 0)
        @php($suma_valor_concepto15 = 0)
        @php($suma_valor_concepto16 = 0)
        @php($suma_valor_concepto18 = 0)
        @foreach($registros as $prescribe)
            <tr>
                <td style="text-align: center;" width="20">{{ substr($prescribe->created_at, 0, 10) }}</td>
                <td style="text-align: center;" width="27">{{ $prescribe->codigo_predio }}</td>
                <td style="text-align: center;" width="15">{{ $prescribe->prescribe_anio }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $prescribe->valor_concepto1 }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $prescribe->valor_concepto2 }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $prescribe->valor_concepto3 }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $prescribe->valor_concepto4 }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $prescribe->valor_concepto13 }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $prescribe->valor_concepto15 }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $prescribe->valor_concepto16 }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $prescribe->valor_concepto18 }}</td>
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
        <tr>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" colspan="3">TOTAL PAGO</th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $suma_valor_concepto1 }}</th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $suma_valor_concepto2 }}</th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $suma_valor_concepto3 }}</th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $suma_valor_concepto4 }}</th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $suma_valor_concepto13 }}</th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $suma_valor_concepto15 }}</th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $suma_valor_concepto16 }}</th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $suma_valor_concepto18 }}</th>
        </tr>
        @else
        <tr>
            <td colspan="11" style="text-align: center; font-size: 110%;">NO HAY INFORMACI&Oacute;N DISPONIBLE</td>
        </tr>
        @endif
    </tbody>
</table>
