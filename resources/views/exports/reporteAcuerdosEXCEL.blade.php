<table style="width: 100%;">
    <thead>
        <tr style="border: 1px solid #000;">
            <td style="border: 1px solid #000; width: 20%; padding: 10px;">
                <img src="{{ public_path('/theme/plugins/images/'.$logo) }}" width="auto" height="120px" alt="logo" class="dark-logo" style="margin: 10px;" />
            </td>
            <td style="border: 1px solid #000; width: 80%; text-align: center; vertical-align: middle;" colspan="7">
                <h4><b>{{ strtoupper($alcaldia) }}</b></h4>
                <h4><b>NIT {{ $nit }}</b></h4>
                <h4><b>IMPUESTO PREDIAL UNIFICADO</b></h4>
                <h4><b>REPORTE DE ACUERDOS DE PAGO</b></h4>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr><td colspan="8" height="25"></td></tr>
        <tr>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>C&Oacute;DIGO PREDIO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>N&Uacute;MERO ACUERDO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>RESOLUCI&Oacute;N</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>VIGENCIAS</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>CUOTAS</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>VALOR ACUERDO</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>USUARIO CREA</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>FECHA CREACI&Oacute;N</b></th>
        </tr>
        @if (count($registros) > 0)
        @foreach($registros as $acuerdo)
            <tr>
                <td style="text-align: center; border: 1px solid #000;" width="30">{{ $acuerdo->codigo_predio }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="20">{{ $acuerdo->numero_acuerdo }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="20">{{ $acuerdo->numero_resolucion_acuerdo }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="15">{{ $acuerdo->anio_inicial_acuerdo }} - {{ $acuerdo->anio_final_acuerdo }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="10">{{ $acuerdo->cuotas_acuerdo }}</td>
                <td style="text-align: right; border: 1px solid #000;" width="20" data-format="$#,##0_-">{{ $acuerdo->total_acuerdo }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="35">{{ $acuerdo->nombres }} {{ $acuerdo->apellidos }}</td>
                <td style="text-align: center; border: 1px solid #000;" width="23">{{ $acuerdo->created_at }}</td>
            </tr>
        @endforeach
        @else
        <tr>
            <td colspan="8" style="text-align: center; font-size: 110%;">NO HAY INFORMACI&Oacute;N DISPONIBLE</td>
        </tr>
        @endif
    </tbody>
</table>
