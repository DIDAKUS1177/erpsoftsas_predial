<table style="width: 100%;">
    <thead>
        <tr style="border: 1px solid #000;">
            <td style="border: 1px solid #000; width: 88%; padding: 10px;" colspan="2" rowspan="3">
                <img src="{{ public_path('/theme/plugins/images/'.$logo) }}" width="auto" height="120px" alt="logo" class="dark-logo" style="margin: 10px;" />
            </td>
            <td style="border: 1px solid #000; width: 88%; text-align: center; vertical-align: middle;" colspan="2" rowspan="3">
                <h4><b>{{ $alcaldia }}</b></h4>
                <h5>{{ $nit }}</h5>
                <h5>{{ $direccion }}</h5>
                <h5>{{ $ubicacion }}</h5>
            </td>
            <td style="border: 1px solid #000; width: 88%; text-align: center; vertical-align: middle;" colspan="3" rowspan="3">
                <h3><b>RELACI&Oacute;N DE CARTERA</b></h3>
                <h3><b>IMPUESTO PREDIAL</b></h3>
                <br />
                <h4>{{ $fecha }}</h4>
            </td>
            <td colspan="2" style="border: 1px solid #000; vertical-align: middle;" height="35">C&oacute;digo:</td>
        </tr>
        <tr style="border: 1px solid #000;">
            <td colspan="2" style="border: 1px solid #000; vertical-align: middle;" height="35">Versi&oacute;n:</td>
        </tr>
        <tr style="border: 1px solid #000;">
            <td colspan="2" style="border: 1px solid #000; vertical-align: middle;" height="35">Registros: {{ count($registros) }}</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align: center; vertical-align: middle;" colspan="9" height="25"><h3><b>RELACI&Oacute;N DE CARTERA IMPUESTO PREDIAL</b></h3></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: middle;" colspan="9" height="25"><h3><b>VIGENCIA {{ $anio }}</b></h3></td>
        </tr>
        <tr>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>A&ntilde;o</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Factura</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>C&oacute;digo predio</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Propietario</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Predial presente a&ntilde;o</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Inter&eacute;s predial</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Car</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Inter&eacute;s Car</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Total vigencia</b></th>
        </tr>
        @php($predial = 0)
        @php($interespredial = 0)
        @php($car = 0)
        @php($interescar = 0)
        @php($totalvigencia = 0)
        @foreach($registros as $registro)
        <tr>
            <td style="text-align: center;" width="8">{{ $registro->ultimo_anio }}</td>
            <td style="text-align: center;" width="13">{{ $registro->factura_pago }}</td>
            <td style="text-align: center;" width="27">{{ $registro->codigo_predio }}</td>
            <td width="60">{{ $registro->nombre_propietario }}</td>
            <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->predial }}</td>
            <td style="text-align: right;" width="17" data-format="$#,##0_-">{{ $registro->interespredial }}</td>
            <td style="text-align: right;" width="17" data-format="$#,##0_-">{{ $registro->car }}</td>
            <td style="text-align: right;" width="17" data-format="$#,##0_-">{{ $registro->interescar }}</td>
            <td style="text-align: right;" width="17" data-format="$#,##0_-">{{ $registro->totalvigencia }}</td>
        </tr>
        @php($predial += $registro->predial)
        @php($interespredial += $registro->interespredial)
        @php($car += $registro->car)
        @php($interescar += $registro->interescar)
        @php($totalvigencia += $registro->totalvigencia)
        @endforeach
        <tr>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" colspan="4"><b>TOTAL:</b></td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $predial }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $interespredial }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $car }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $interescar }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $totalvigencia }}</td>
        </tr>
    </tbody>
</table>
