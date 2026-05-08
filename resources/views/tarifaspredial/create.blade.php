@extends('theme.default')
@section('content')

@push('scripts')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\TarifasPredialCreateFormRequest', '#create-form'); !!}
    {!! JsValidator::formRequest('App\Http\Requests\TarifasPredialUpdateFormRequest', '#update-form'); !!}
    <script src="{!! asset('theme/js/accounting.min.js') !!}"></script>
    <script src="{!! asset('theme/js/autonumeric.min.js') !!}"></script>
    <script src="{!! asset('theme/js/customjs/controlsite.js') !!}"></script>
@endpush
@if(Session::get('tab_current'))
<input type="hidden" id="tab" value="{{ Session::get('tab_current') }}">
@elseif($tab_current)
<input type="hidden" id="tab" value="{{ $tab_current }}">
@endif
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-5 col-md-8 col-sm-12 col-xs-12">
            <h4 class="page-title">{!! $opcion->descripcion !!}</h4>
        </div>
        <div class="col-lg-7 col-md-4 col-sm-12 col-xs-12">
            <!-- <button class="right-side-toggle waves-effect waves-light btn-info btn-circle pull-right m-l-20">
                <i class="ti-settings text-white"></i>
            </button> -->
            <!-- <a href="#" target="" class="btn btn-danger pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light">Buy Admin Now</a> -->
            <ol class="breadcrumb">
                <!-- <li><a href="#">Dashboard</a></li> -->
                <li class="active">{{ Session::get('desc_role') }}</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <section>
                    <div class="sttabs tabs-style-bar">
                        <nav>
                            <ul>
                                <li id="li-section-bar-1" class="tab-current"><a href="#section-bar-1" class="sticon ti-pulse"><span>Nueva tarifa predial</span></a></li>
                                <li id="li-section-bar-2" class=""><a href="#section-bar-2" class="sticon icon-list"><span>Listado de tarifas predial</span></a></li>
                                <!-- <li class=""><a href="#section-bar-3" class="sticon ti-stats-up"><span>Analytics</span></a></li>
                                <li class=""><a href="#section-bar-4" class="sticon ti-upload"><span>Upload</span></a></li>
                                <li class=""><a href="#section-bar-5" class="sticon ti-settings"><span>Settings</span></a></li> -->
                            </ul>
                        </nav>
                        <div class="content-wrap">
                            <section id="section-bar-1" class="content-current">
                                <div class="panel panel-inverse">
                                    <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Informaci&oacute;n de la tarifa predial</div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <form action="{{ route('tarifaspredial.create_tarifaspredial') }}" method="post" id="create-form">
                                                @csrf
                                                <div class="result">
                                                    @if(Session::get('success'))
                                                        <div class="alert alert-success">
                                                            {!! Session::get('success') !!}
                                                        </div>
                                                    @endif
                                                    @if(Session::get('fail'))
                                                        <div class="alert alert-danger">
                                                            {!! Session::get('fail') !!}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="form-body">
                                                    <!-- <h3 class="box-title">Informaci&oacute;n de la tarifa predial</h3> -->
                                                    <!-- <hr> -->
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">A&ntilde;o</label>
                                                                <input type="text" id="anio" name="anio" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese a&ntilde;o" value="{{ old('anio') }}" maxlength="4">
                                                                <span class="text-danger">@error('anio') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo</label>
                                                                <input type="text" id="codigo" name="codigo" class="form-control" autocomplete="off" placeholder="Ingrese c&oacute;digo" value="{{ old('codigo') }}" maxlength="10">
                                                                <span class="text-danger">@error('codigo') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Meses aministia</label>
                                                                <input type="text" id="meses_amnistia" name="meses_amnistia" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese mes aministia" value="{{ old('meses_amnistia') }}">
                                                                <span class="text-danger">@error('meses_amnistia') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div> --}}
                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Rango</label>
                                                                <input type="text" id="rango" name="rango" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese rango" value="{{ old('rango') }}" maxlength="3">
                                                                <span class="text-danger">@error('rango') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Descripci&oacute;n</label>
                                                                <input type="text" id="descripcion" name="descripcion" class="form-control" autocomplete="off" placeholder="Ingrese descripci&oacute;n" value="{{ old('descripcion') }}" maxlength="128">
                                                                <span class="text-danger">@error('descripcion') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Aval&uacute;o inicial:</label>
                                                                <input type="text" id="avaluo_inicial" name="avaluo_inicial" class="form-control" autocomplete="off" placeholder="Ingrese aval&uacute;o inicial" value="{{ old('avaluo_inicial') }}">
                                                                <span class="text-danger">@error('avaluo_inicial') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Aval&uacute;o final:</label>
                                                                <input type="text" id="avaluo_final" name="avaluo_final" class="form-control" autocomplete="off" placeholder="Ingrese aval&uacute;o final" value="{{ old('avaluo_final') }}">
                                                                <span class="text-danger">@error('avaluo_final') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tarifa:</label>
                                                                <input type="text" id="tarifa" name="tarifa" class="form-control" autocomplete="off" placeholder="Ingrese tarifa" value="{{ old('tarifa') }}">
                                                                <span class="text-danger">@error('tarifa') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">% CAR:</label>
                                                                <input type="text" id="porcentaje_car" name="porcentaje_car" class="form-control porcentaje" autocomplete="off" placeholder="Ingrese porcentaje CAR" value="{{ old('porcentaje_car') }}">
                                                                <span class="text-danger">@error('porcentaje_car') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="display: none;">
                                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Estrato:</label>
                                                                <input type="text" id="estrato" name="estrato" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese estrato" value="{{ old('estrato') }}">
                                                                <span class="text-danger">@error('estrato') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Destino</label>
                                                                <input type="text" id="destino" name="destino" class="form-control" autocomplete="off" placeholder="Ingrese destino" value="{{ old('destino') }}" maxlength="128">
                                                                <span class="text-danger">@error('destino') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Grupo tarifa:</label>
                                                                <input type="text" id="grupo_tarifa" name="grupo_tarifa" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese grupo tarifa" value="{{ old('grupo_tarifa') }}">
                                                                <span class="text-danger">@error('grupo_tarifa') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="row">
                                                        <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tipo identificaci&oacute;n</label>
                                                                <select id="tid_acu" name="tid_acu" class="form-control selectpicker" title="Seleccione...">
                                                                    <option value="CC" {{ old('tid_acu') == "CC" || old('tid_acu') == null ? 'selected' : '' }}>C&eacute;dula</option>
                                                                    <option value="TI" {{ old('tid_acu') == "TI" ? 'selected' : '' }}>Tarjeta Identidad</option>
                                                                    <option value="CE" {{ old('tid_acu') == "CE" ? 'selected' : '' }}>C&eacute;dula extranjer&iacute;a</option>
                                                                    <option value="PS" {{ old('tid_acu') == "PS" ? 'selected' : '' }}>Pasaporte</option>
                                                                    <option value="RC" {{ old('tid_acu') == "RC" ? 'selected' : '' }}>Registro Civ&iacute;l</option>
                                                                </select>
                                                                <span class="text-danger">@error('tid_acu') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Identificaci&oacute;n</label>
                                                                <input type="text" id="ide_acu" name="ide_acu" class="form-control onlyNumbers" placeholder="Ingrese identificaci&oacute;n" value="{{ old('ide_acu') }}">
                                                                <span class="text-danger">@error('ide_acu') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tel&eacute;fono</label>
                                                                <input type="text" id="tel_acu" name="tel_acu" class="form-control onlyNumbers" placeholder="Ingrese tel&eacute;fono" value="{{ old('tel_acu') }}">
                                                                <span class="text-danger">@error('tel_acu') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                                <label class="control-label">Direcci&oacute;n</label>
                                                                <input type="text" id="dir_acu" name="dir_acu" class="form-control" placeholder="Ingrese la direcci&oacute;n" value="{{ old('dir_acu') }}">
                                                                <span class="text-danger">@error('dir_acu') {{ $message }} @enderror</span>
                                                            </div>

                                                        </div>
                                                    </div> --}}

                                                </div>
                                                <div class="form-actions m-t-20">
                                                    <button type="submit" class="btn btn-info"> <i class="fa fa-save"></i> Guardar informaci&oacute;n</button>
                                                    <!-- <button type="button" class="btn btn-default">Cancelar</button> -->
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <section id="section-bar-2" class="">
                                <div id="div_table" class="row">
                                    <div class="col-lg-12">
                                        <div class="well">
                                            @if(isset($tarifas_predial))
                                                <div class="result">
                                                    @if(Session::get('success'))
                                                        <div class="alert alert-success">
                                                            {!! Session::get('success') !!}
                                                        </div>
                                                    @endif
                                                    @if(Session::get('fail'))
                                                        <div class="alert alert-danger">
                                                            {!! Session::get('fail') !!}
                                                        </div>
                                                    @endif
                                                </div>
                                                <h2>Lista de tarifas predial</h2>
                                                <table id="myTable" class="table table-hover table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="cell_center" style="width: 7%;">A&ntilde;o</th>
                                                            <th class="cell_center" style="width: 7%;">C&oacute;digo</th>
                                                            {{-- <th class="cell_center" style="width: 7%;">Meses amnistia</th> --}}
                                                            <th class="cell_center" style="width: 7%;">Rango</th>
                                                            <th class="cell_center">Descripci&oacute;n</th>
                                                            <th class="cell_center" style="width: 10%;">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(count($tarifas_predial) > 0)
                                                            @foreach($tarifas_predial as $tarifa_predial)
                                                            <tr style="cursor: pointer;" json-data='@json($tarifa_predial)'>
                                                                {{-- <td class="cell_center edit_row">{{ $tarifa_predial->ide_acu }}</td>
                                                                <td class="cell_center edit_row">{{ $tarifa_predial->tid_acu }}</td> --}}
                                                                <td class="edit_row cell_center">{{ $tarifa_predial->anio }}</td>
                                                                <td class="edit_row cell_center">{{ $tarifa_predial->codigo }}</td>
                                                                {{-- <td class="edit_row cell_center">{{ $tarifa_predial->meses_amnistia }}</td> --}}
                                                                <td class="edit_row cell_center">{{ $tarifa_predial->rango }}</td>
                                                                <td class="edit_row">{{ $tarifa_predial->descripcion }}</td>
                                                                {{-- <td class="cell_center edit_row">{{ $tarifa_predial->tel_acu }}</td>
                                                                <td class="edit_row">{{ $tarifa_predial->dir_acu }}</td> --}}
                                                                <td class="cell_center">
                                                                    <button type="button" ide="{{ $tarifa_predial->id }}" class="modify_row btn btn-info"><i class="fa fa-pencil-square"></i></button>
                                                                    &nbsp;&nbsp;
                                                                    <button type="button" ide="{{ $tarifa_predial->id }}" class="delete_row btn btn-inverse"><i class="fa fa-trash-o"></i></button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        {{-- @else
                                                        <tr>
                                                            <td colspan="7">No hay informaci&oacute;n para mostrar</td>
                                                        </tr> --}}
                                                        @endif
                                                    </tbody>
                                                </table>
                                                {{-- <div class="pagination-blobk">
                                                    {{ $tarifas_predial->links('layouts.paginationlinks') }}
                                                </div> --}}
                                                <form id="form_delete" action="{{ route('tarifaspredial.delete_tarifaspredial') }}" method="post" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" id="input_delete" name="input_delete">
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div id="div_edit_form" class="panel panel-info" style="display: none;">
                                    <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Actualizar informaci&oacute;n de la tarifa predial</div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <form action="{{ route('tarifaspredial.update_tarifaspredial') }}" method="post" id="update-form">
                                                @csrf

                                                <div class="form-body">
                                                    <input type="hidden" id="id_edit" name="id_edit" value="{{ old('id_edit') }}">
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">A&ntilde;o</label>
                                                                <input type="text" id="anio_edit" name="anio_edit" class="form-control onlyNumbers" placeholder="Ingrese a&ntilde;o" value="{{ old('anio_edit') }}" readonly="readonly">
                                                                <span class="text-danger">@error('anio_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo</label>
                                                                <input type="text" id="codigo_edit" name="codigo_edit" class="form-control" autocomplete="off" placeholder="Ingrese c&oacute;digo" value="{{ old('codigo_edit') }}" readonly="readonly">
                                                                <span class="text-danger">@error('codigo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Meses aministia</label>
                                                                <input type="text" id="meses_amnistia_edit" name="meses_amnistia_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese mes aministia" value="{{ old('meses_amnistia_edit') }}">
                                                                <span class="text-danger">@error('meses_amnistia_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div> --}}
                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Rango</label>
                                                                <input type="text" id="rango_edit" name="rango_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese rango" value="{{ old('rango_edit') }}" readonly="readonly">
                                                                <span class="text-danger">@error('rango_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Descripci&oacute;n</label>
                                                                <input type="text" id="descripcion_edit" name="descripcion_edit" class="form-control" autocomplete="off" placeholder="Ingrese descripcion" value="{{ old('descripcion_edit') }}" maxlength="128">
                                                                <span class="text-danger">@error('descripcion_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Aval&uacute;o inicial:</label>
                                                                <input type="text" id="avaluo_inicial_edit" name="avaluo_inicial_edit" class="form-control" autocomplete="off" placeholder="Ingrese aval&uacute;o inicial" value="{{ old('avaluo_inicial_edit') }}">
                                                                <span class="text-danger">@error('avaluo_inicial_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Aval&uacute;o final:</label>
                                                                <input type="text" id="avaluo_final_edit" name="avaluo_final_edit" class="form-control" autocomplete="off" placeholder="Ingrese aval&uacute;o final" value="{{ old('avaluo_final_edit') }}">
                                                                <span class="text-danger">@error('avaluo_final_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tarifa:</label>
                                                                <input type="text" id="tarifa_edit" name="tarifa_edit" class="form-control" autocomplete="off" placeholder="Ingrese tarifa" value="{{ old('tarifa_edit') }}">
                                                                <span class="text-danger">@error('tarifa_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">% CAR:</label>
                                                                <input type="text" id="porcentaje_car_edit" name="porcentaje_car_edit" class="form-control porcentaje" autocomplete="off" placeholder="Ingrese porcentaje CAR" value="{{ old('porcentaje_car_edit') }}">
                                                                <span class="text-danger">@error('porcentaje_car_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="display: none;">
                                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Estrato:</label>
                                                                <input type="text" id="estrato_edit" name="estrato_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese estrato" value="{{ old('estrato_edit') }}">
                                                                <span class="text-danger">@error('estrato_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Destino</label>
                                                                <input type="text" id="destino_edit" name="destino_edit" class="form-control" autocomplete="off" placeholder="Ingrese destino" value="{{ old('destino_edit') }}" maxlength="128">
                                                                <span class="text-danger">@error('destino_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Grupo tarifa:</label>
                                                                <input type="text" id="grupo_tarifa_edit" name="grupo_tarifa_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese grupo tarifa" value="{{ old('grupo_tarifa_edit') }}">
                                                                <span class="text-danger">@error('grupo_tarifa_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="row">
                                                        <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tipo identificaci&oacute;n</label>
                                                                <select id="tid_acu_edit" name="tid_acu_edit" class="form-control selectpicker" title="Seleccione...">
                                                                    <option value="CC" {{ old('tid_acu_edit') == "CC" ? 'selected' : '' }}>C&eacute;dula</option>
                                                                    <option value="TI" {{ old('tid_acu_edit') == "TI" ? 'selected' : '' }}>Tarjeta Identidad</option>
                                                                    <option value="CE" {{ old('tid_acu_edit') == "CE" ? 'selected' : '' }}>C&eacute;dula extranjer&iacute;a</option>
                                                                    <option value="PS" {{ old('tid_acu_edit') == "PS" ? 'selected' : '' }}>Pasaporte</option>
                                                                    <option value="RC" {{ old('tid_acu_edit') == "RC" ? 'selected' : '' }}>Registro Civ&iacute;l</option>
                                                                </select>
                                                                <span class="text-danger">@error('tid_acu_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Identificaci&oacute;n</label>
                                                                <input type="text" id="ide_acu_edit" name="ide_acu_edit" readonly="" class="form-control onlyNumbers" placeholder="Ingrese identificaci&oacute;n" value="{{ old('ide_acu_edit') }}">
                                                                <span class="text-danger">@error('ide_acu_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tel&eacute;fono</label>
                                                                <input type="text" id="tel_acu_edit" name="tel_acu_edit" class="form-control onlyNumbers" placeholder="Ingrese tel&eacute;fono" value="{{ old('tel_acu_edit') }}">
                                                                <span class="text-danger">@error('tel_acu_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Direcci&oacute;n</label>
                                                                <input type="text" id="dir_acu_edit" name="dir_acu_edit" class="form-control" placeholder="Ingrese la direcci&oacute;n" value="{{ old('dir_acu_edit') }}">
                                                                <span class="text-danger">@error('dir_acu_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                                <div class="form-actions m-t-20">
                                                    <button type="submit" class="btn btn-info"> <i class="fa fa-save"></i> Actualizar informaci&oacute;n</button>
                                                    <button id="btn_cancel_edit" type="button" class="btn btn-default"> <i class="fa fa-thumbs-down"></i> Cancelar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- <section id="section-bar-3" class="">
                                <h2>Tabbing 3</h2></section>
                            <section id="section-bar-4" class="">
                                <h2>Tabbing 4</h2></section>
                            <section id="section-bar-5" class="">
                                <h2>Tabbing 5</h2></section> -->
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
