<table style="width: 100%;">
    <thead>
        <tr style="border: 1px solid #000;">
            <td style="border: 1px solid #000; width: 20%; padding: 10px;" colspan="1">
                <img src="{{ public_path('/theme/plugins/images/'.$logo) }}" width="auto" height="120px" alt="logo" class="dark-logo" style="margin: 10px; padding-left: 10px;" />
            </td>
            <td style="border: 1px solid #000; width: 88%; text-align: center; vertical-align: middle;" colspan="8">
                <h4><b>REP&Uacute;BLICA DE COLOMBIA</b></h4>
                <h4><b>{{ strtoupper($alcaldia) }}</b></h4>
                <h4><b>NIT {{ $nit }}</b></h4>
                <h4><b>HISTORICO DE PAGOS</b></h4>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr><td colspan="9" height="25"></td></tr>
        <tr>
            <th style="background-color: #ededed; text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="2" height="25"><b>C&eacute;dula catastral:</b></th>
            <td style="text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="7" height="25">{{ $predio->codigo_predio }}</td>
        </tr>
        <tr>
            <th style="background-color: #ededed; text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="2" height="25"><b>Propietario:</b></th>
            <td style="text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="7" height="25">{{ $propietario_ppal->nombre}}</td>
        </tr>
        <tr>
            <th style="background-color: #ededed; text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="2" height="25"><b>Direcci&oacute;n:</b></th>
            <td style="text-align: left; vertical-align: middle; border: 1px solid #000;" colspan="7" height="25">{{ $predio->direccion }}</td>
        </tr>
        <tr><td colspan="9" height="25"></td></tr>
        <tr>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>RECIBO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>FECHA</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>BANCO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>VALOR</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>A&Ntilde;O</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>AVALUO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>TIPO RECIBO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>PRESCRITO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>EXCENTO</b></th>
        </tr>
        @if (count($registros) > 0)
        @php($suma_impuesto = 0)
        @foreach($registros as $avaluo)
            <tr>
                <td style="text-align: center; border: 1px solid #000;" width="18">{{ $avaluo->factura_pago }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="15">{{ substr($avaluo->fecha_pago, 0, 10) }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="40">{{ $avaluo->banco }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="15" data-format="$#,##0_-">{{ $avaluo->valor_pago }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="12">{{ $avaluo->anio }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="18" data-format="$#,##0_-">{{ $avaluo->avaluo }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="14">Vigencia</td>
                <td style="text-align: center; border: 1px solid #000;" width="12">{{ $avaluo->prescrito }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="12">{{ $avaluo->exencion }}</td>
            </tr>
            @php($suma_impuesto += $avaluo->valor_pago)
        @endforeach
        <tr>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" colspan="3"><b>TOTAL PAGO</b></th>
            <th style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-"><b>{{ $suma_impuesto }}</b></th>
            <th style="text-align: right; vertical-align: middle;" colspan="5"></th>
        </tr>
        @else
        <tr>
            <td colspan="9" style="text-align: center; font-size: 110%;">NO HAY INFORMACI&Oacute;N DISPONIBLE</td>
        </tr>
        @endif
    </tbody>
</table>
