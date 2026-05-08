<table style="width: 100%;">
    <thead>
        <tr style="border: 1px solid #000;">
            <td style="border: 1px solid #000; width: 88%; padding: 10px;" colspan="2" rowspan="3">
                <img src="{{ public_path('/theme/plugins/images/'.$logo) }}" width="auto" height="120px" alt="logo" class="dark-logo" style="margin: 10px;" />
            </td>
            <td style="border: 1px solid #000; width: 88%; text-align: center; vertical-align: middle;" rowspan="3">
                <h4><b>{{ $alcaldia }}</b></h4>
                <h5>{{ $nit }}</h5>
                <h5>{{ $direccion }}</h5>
                <h5>{{ $ubicacion }}</h5>
            </td>
            <td style="border: 1px solid #000; width: 88%; text-align: center; vertical-align: middle;" colspan="12" rowspan="3">
                <h3><b>RELACI&Oacute;N DE INGRESOS PREDIAL POR RECIBO Y A&Ntilde;O</b></h3>
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
            <td style="text-align: center; vertical-align: middle;" colspan="17" height="25"><h3><b>RELACI&Oacute;N DE INGRESOS PREDIAL POR RECIBO Y A&Ntilde;O</b></h3></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: middle;" colspan="17" height="25"><h3><b>ENTRE FECHAS {{ $fecha_inicial }} Y {{ $fecha_final }}</b></h3></td>
        </tr>
        <tr>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>A&ntilde;o</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Factura</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>C&oacute;digo predio</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Propietario</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Predial a&ntilde;o act.</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Desc. predial</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Predial a&ntilde;os ant.</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Int. predial</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Int. a&ntilde;os ant.</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Total predial</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Car a&ntilde;o act.</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Desc. Car</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Car a&ntilde;os ant.</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Int. Car a&ntilde;o act.</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Int. Car a&ntilde;os ant.</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Total Car</b></th>
            <th style="background-color: #ededed; text-align: center; vertical-align: middle; border: 1px solid #000;" height="25"><b>Valor facturado</b></th>
        </tr>
        @php($predialanoactual = 0)
        @php($descuentopredial = 0)
        @php($predialanosanteriores = 0)
        @php($interesespredial = 0)
        @php($interesespredialanosanteriores = 0)
        @php($total_predial = 0)
        @php($caranoactual = 0)
        @php($descuentocar = 0)
        @php($caranoanteriores = 0)
        @php($interesescaractual = 0)
        @php($interesescaranteriores = 0)
        @php($totalcar = 0)
        @php($valor_facturado = 0)
        @foreach($registros as $registro)
            <tr>
                <td style="text-align: center;" width="8">{{ $registro->ultimo_anio }}</td>
                <td style="text-align: center;" width="13">{{ $registro->factura_pago }}</td>
                <td style="text-align: center;" width="27">{{ $registro->codigo_predio }}</td>
                <td width="60">{{ $registro->nombre_propietario }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->predialanoactual }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->descuentopredial }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->predialanosanteriores }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->interesespredial }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->interesespredialanosanteriores }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->total_predial }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->caranoactual }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->descuentocar }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->caranoanteriores }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->interesescaractual }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->interesescaranteriores }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->totalcar }}</td>
                <td style="text-align: right;" width="20" data-format="$#,##0_-">{{ $registro->valor_facturado }}</td>
            </tr>
            @php($predialanoactual += $registro->predialanoactual)
            @php($descuentopredial += $registro->descuentopredial)
            @php($predialanosanteriores += $registro->predialanosanteriores)
            @php($interesespredial += $registro->interesespredial)
            @php($interesespredialanosanteriores += $registro->interesespredialanosanteriores)
            @php($total_predial += $registro->total_predial)
            @php($caranoactual += $registro->caranoactual)
            @php($descuentocar += $registro->descuentocar)
            @php($caranoanteriores += $registro->caranoanteriores)
            @php($interesescaractual += $registro->interesescaractual)
            @php($interesescaranteriores += $registro->interesescaranteriores)
            @php($totalcar += $registro->totalcar)
            @php($valor_facturado += $registro->valor_facturado)
        @endforeach
        <tr>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" colspan="4"><b>TOTAL:</b></td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $predialanoactual }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $descuentopredial }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $predialanosanteriores }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $interesespredial }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $interesespredialanosanteriores }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $total_predial }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $caranoactual }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $descuentocar }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $caranoanteriores }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $interesescaractual }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $interesescaranteriores }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $totalcar }}</td>
            <td style="background-color: #ededed; text-align: right; vertical-align: middle; border: 1px solid #000;" data-format="$#,##0_-">{{ $valor_facturado }}</td>
        </tr>
    </tbody>
</table>
