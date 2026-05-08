<table style="width: 100%;">
    <thead>
        <tr style="border: 1px solid #000;">
            <td style="border: 1px solid #000; width: 20%; padding: 10px;">
                <img src="{{ public_path('/theme/plugins/images/'.$logo) }}" width="auto" height="120px" alt="logo" class="dark-logo" style="margin: 10px;" />
            </td>
            <td style="border: 1px solid #000; width: 80%; text-align: center; vertical-align: middle;" colspan="8">
                <h4><b>{{ strtoupper($alcaldia) }}</b></h4>
                <h4><b>NIT {{ $nit }}</b></h4>
                <h4><b>IMPUESTO PREDIAL UNIFICADO</b></h4>
                <h4><b>DISCRIMINACIÓN DE CUOTAS - ACUERDO DE PAGO</b></h4>
                @if($acuerdo_pago)
                <h4>Acuerdo No. {{ $acuerdo_pago->numero_acuerdo }} &nbsp;|&nbsp; Predio: {{ $acuerdo_pago->codigo_predio }} &nbsp;|&nbsp; Vigencias: {{ $acuerdo_pago->anio_inicial_acuerdo }} - {{ $acuerdo_pago->anio_final_acuerdo }}</h4>
                @endif
            </td>
        </tr>
    </thead>
    <tbody>
        <tr><td colspan="9" height="25"></td></tr>
        <tr>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>N° CUOTA</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>FACTURA</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>VALOR CUOTA</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PAGADO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>FECHA PAGO PACTADA</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>FECHA REAL PAGO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>BANCO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>IMPUESTO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>INTERESES</b></th>
        </tr>
        @if (count($registros) > 0)
        @foreach($registros as $cuota)
            <tr>
                <td style="text-align: center; border: 1px solid #000;" width="10">{{ $cuota->cuota_numero }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="20">{{ $cuota->factura_pago ?? 'Sin asignar' }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="20" data-format="$#,##0_-">{{ $cuota->valor_cuota }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="10">{{ $cuota->pagado }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="20">{{ $cuota->fecha_pago }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="20">{{ $cuota->fecha_real_pago }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="25">{{ $cuota->banco }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="20" data-format="$#,##0_-">{{ $cuota->valor_concepto1 + $cuota->valor_concepto3 }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="20" data-format="$#,##0_-">{{ $cuota->valor_concepto2 + $cuota->valor_concepto4 + $cuota->valor_concepto14 }}</td>
            </tr>
        @endforeach
        @else
        <tr>
            <td colspan="9" style="text-align: center; font-size: 110%;">NO HAY INFORMACIÓN DISPONIBLE</td>
        </tr>
        @endif
    </tbody>
</table>
