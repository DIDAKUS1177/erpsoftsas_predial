@extends('theme.default')
@section('content')

@push('scripts')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\ConceptosPredioCreateFormRequest', '#create-form'); !!}
    {!! JsValidator::formRequest('App\Http\Requests\ConceptosPredioUpdateFormRequest', '#update-form'); !!}
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
                                <li id="li-section-bar-1" class="tab-current"><a href="#section-bar-1" class="sticon ti-pulse"><span>Nuevo concepto de predio</span></a></li>
                                <li id="li-section-bar-2" class=""><a href="#section-bar-2" class="sticon icon-list"><span>Listado de conceptos de predio</span></a></li>
                                <!-- <li class=""><a href="#section-bar-3" class="sticon ti-stats-up"><span>Analytics</span></a></li>
                                <li class=""><a href="#section-bar-4" class="sticon ti-upload"><span>Upload</span></a></li>
                                <li class=""><a href="#section-bar-5" class="sticon ti-settings"><span>Settings</span></a></li> -->
                            </ul>
                        </nav>
                        <div class="content-wrap">
                            <section id="section-bar-1" class="content-current">
                                <div class="panel panel-inverse">
                                    <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Informaci&oacute;n del concepto de predio</div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <form action="{{ route('conceptospredio.create_conceptospredio') }}" method="post" id="create-form">
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
                                                    <!-- <h3 class="box-title">Informaci&oacute;n de la concepto de predio</h3> -->
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
                                                                <input type="text" id="codigo" name="codigo" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese c&oacute;digo" value="{{ old('codigo') }}" maxlength="3">
                                                                <span class="text-danger">@error('codigo') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Meses aministia</label>
                                                                <input type="text" id="meses_amnistia" name="meses_amnistia" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese mes aministia" value="{{ old('meses_amnistia') }}">
                                                                <span class="text-danger">@error('meses_amnistia') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Prioridad</label>
                                                                <input type="text" id="prioridad" name="prioridad" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese prioridad" value="{{ old('prioridad') }}" maxlength="3">
                                                                <span class="text-danger">@error('prioridad') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre</label>
                                                                <input type="text" id="nombre" name="nombre" class="form-control" autocomplete="off" placeholder="Ingrese nombre" value="{{ old('nombre') }}" maxlength="128">
                                                                <span class="text-danger">@error('nombre') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Formula</label>
                                                                <input type="text" id="formula" name="formula" class="form-control" autocomplete="off" placeholder="Ingrese formula" value="{{ old('formula') }}" maxlength="1024">
                                                                <span class="text-danger">@error('formula') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group" style="margin-bottom: 0px;">
                                                                {{-- <input type="hidden" id="aplica_interes" name="aplica_interes" value="{{ old('aplica_interes') }}"> --}}
                                                                <input type="checkbox" id="aplica_interes" name="aplica_interes" value="{{ old('aplica_interes') }}">
                                                                <label for="aplica_interes_check" class="control-label" style="padding-left: 10px;">¿Aplica inter&eacute;s?</label>
                                                                {{-- <span class="text-danger">@error('aplica_interes') {{ $message }} @enderror</span> --}}
                                                            </div>
                                                            <span id="span_aplica_interes" class="text-muted">
                                                                NO
                                                            </span>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            {{-- <div class="form-group">
                                                                <label class="control-label">Capital:</label>
                                                                <input type="text" id="capital" name="capital" class="form-control" autocomplete="off" placeholder="Ingrese capital" value="{{ old('capital') }}">
                                                                <span class="text-danger">@error('capital') {{ $message }} @enderror</span>
                                                            </div> --}}

                                                            <div class="form-group" style="margin-bottom: 0px;">
                                                                {{-- <input type="hidden" id="capital" name="capital" value="{{ old('capital') }}"> --}}
                                                                <input type="checkbox" id="capital" name="capital" value="{{ old('capital') }}">
                                                                <label for="capital_check" class="control-label" style="padding-left: 10px;">¿Capital?</label>
                                                                {{-- <span class="text-danger">@error('capital') {{ $message }} @enderror</span> --}}
                                                            </div>
                                                            <span id="span_capital" class="text-muted">
                                                                NO
                                                            </span>

                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            {{-- <div class="form-group">
                                                                <label class="control-label">Inter&eacute;s:</label>
                                                                <input type="text" id="interes" name="interes" class="form-control" autocomplete="off" placeholder="Ingrese inter&eacute;s" value="{{ old('interes') }}">
                                                                <span class="text-danger">@error('interes') {{ $message }} @enderror</span>
                                                            </div> --}}

                                                            <div class="form-group" style="margin-bottom: 0px;">
                                                                {{-- <input type="hidden" id="interes" name="interes" value="{{ old('interes') }}"> --}}
                                                                <input type="checkbox" id="interes" name="interes" value="{{ old('interes') }}">
                                                                <label for="interes_check" class="control-label" style="padding-left: 10px;">¿Inter&eacute;s?</label>
                                                                {{-- <span class="text-danger">@error('interes') {{ $message }} @enderror</span> --}}
                                                            </div>
                                                            <span id="span_interes" class="text-muted">
                                                                NO
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top: 25px;">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">M&iacute;nimo urbano:</label>
                                                                <input type="text" id="minimo_urbano" name="minimo_urbano" class="form-control" autocomplete="off" placeholder="Ingrese m&iacute;nimo urbano" value="{{ old('minimo_urbano') }}">
                                                                <span class="text-danger">@error('minimo_urbano') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">M&iacute;nimo rural:</label>
                                                                <input type="text" id="minimo_rural" name="minimo_rural" class="form-control" autocomplete="off" placeholder="Ingrese m&iacute;nimo rural" value="{{ old('minimo_rural') }}">
                                                                <span class="text-danger">@error('minimo_rural') {{ $message }} @enderror</span>
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
                                            @if(isset($conceptos_predio))
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
                                                <h2>Lista de conceptos de predio</h2>
                                                <table id="myTable" class="table table-hover table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="cell_center" style="width: 7%;">A&ntilde;o</th>
                                                            <th class="cell_center" style="width: 7%;">C&oacute;digo</th>
                                                            <th class="cell_center" style="width: 7%;">Meses amnistia</th>
                                                            <th class="cell_center" style="width: 7%;">Prioridad</th>
                                                            <th class="cell_center">Nombre</th>
                                                            <th class="cell_center" style="width: 10%;">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(count($conceptos_predio) > 0)
                                                            @foreach($conceptos_predio as $concepto_predio)
                                                            <tr style="cursor: pointer;" json-data='@json($concepto_predio)'>
                                                                {{-- <td class="cell_center edit_row">{{ $concepto_predio->ide_acu }}</td>
                                                                <td class="cell_center edit_row">{{ $concepto_predio->tid_acu }}</td> --}}
                                                                <td class="edit_row cell_center">{{ $concepto_predio->anio }}</td>
                                                                <td class="edit_row cell_center">{{ $concepto_predio->codigo }}</td>
                                                                <td class="edit_row cell_center">{{ $concepto_predio->meses_amnistia }}</td>
                                                                <td class="edit_row cell_center">{{ $concepto_predio->prioridad }}</td>
                                                                <td class="edit_row">{{ $concepto_predio->nombre }}</td>
                                                                {{-- <td class="cell_center edit_row">{{ $concepto_predio->tel_acu }}</td>
                                                                <td class="edit_row">{{ $concepto_predio->dir_acu }}</td> --}}
                                                                <td class="cell_center">
                                                                    <button type="button" ide="{{ $concepto_predio->id }}" class="modify_row btn btn-info"><i class="fa fa-pencil-square"></i></button>
                                                                    &nbsp;&nbsp;
                                                                    <button type="button" ide="{{ $concepto_predio->id }}" class="delete_row btn btn-inverse"><i class="fa fa-trash-o"></i></button>
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
                                                    {{ $conceptos_predio->links('layouts.paginationlinks') }}
                                                </div> --}}
                                                <form id="form_delete" action="{{ route('conceptospredio.delete_conceptospredio') }}" method="post" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" id="input_delete" name="input_delete">
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div id="div_edit_form" class="panel panel-info" style="display: none;">
                                    <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Actualizar informaci&oacute;n del concepto de predio</div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <form action="{{ route('conceptospredio.update_conceptospredio') }}" method="post" id="update-form">
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
                                                                <input type="text" id="codigo_edit" name="codigo_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese c&oacute;digo" value="{{ old('codigo_edit') }}" readonly="readonly">
                                                                <span class="text-danger">@error('codigo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Meses aministia</label>
                                                                <input type="text" id="meses_amnistia_edit" name="meses_amnistia_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese mes aministia" value="{{ old('meses_amnistia_edit') }}">
                                                                <span class="text-danger">@error('meses_amnistia_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Prioridad</label>
                                                                <input type="text" id="prioridad_edit" name="prioridad_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese prioridad" value="{{ old('prioridad_edit') }}" maxlength="3">
                                                                <span class="text-danger">@error('prioridad_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre</label>
                                                                <input type="text" id="nombre_edit" name="nombre_edit" class="form-control" autocomplete="off" placeholder="Ingrese nombre" value="{{ old('nombre_edit') }}" maxlength="128">
                                                                <span class="text-danger">@error('nombre_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Formula</label>
                                                                <input type="text" id="formula_edit" name="formula_edit" class="form-control" autocomplete="off" placeholder="Ingrese formula" value="{{ old('formula_edit') }}" maxlength="1024">
                                                                <span class="text-danger">@error('formula_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group" style="margin-bottom: 0px;">
                                                                {{-- <input type="hidden" id="aplica_interes" name="aplica_interes" value="{{ old('aplica_interes') }}"> --}}
                                                                <input type="checkbox" id="aplica_interes_edit" name="aplica_interes_edit" value="{{ old('aplica_interes_edit') }}">
                                                                <label for="aplica_interes_check_edit" class="control-label" style="padding-left: 10px;">¿Aplica inter&eacute;s?</label>
                                                                {{-- <span class="text-danger">@error('aplica_interes') {{ $message }} @enderror</span> --}}
                                                            </div>
                                                            <span id="span_aplica_interes_edit" class="text-muted">
                                                                NO
                                                            </span>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            {{-- <div class="form-group">
                                                                <label class="control-label">Capital:</label>
                                                                <input type="text" id="capital" name="capital" class="form-control" autocomplete="off" placeholder="Ingrese capital" value="{{ old('capital') }}">
                                                                <span class="text-danger">@error('capital') {{ $message }} @enderror</span>
                                                            </div> --}}

                                                            <div class="form-group" style="margin-bottom: 0px;">
                                                                {{-- <input type="hidden" id="capital" name="capital" value="{{ old('capital') }}"> --}}
                                                                <input type="checkbox" id="capital_edit" name="capital_edit" value="{{ old('capital_edit') }}">
                                                                <label for="capital_check_edit" class="control-label" style="padding-left: 10px;">¿Capital?</label>
                                                                {{-- <span class="text-danger">@error('capital') {{ $message }} @enderror</span> --}}
                                                            </div>
                                                            <span id="span_capital_edit" class="text-muted">
                                                                NO
                                                            </span>

                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            {{-- <div class="form-group">
                                                                <label class="control-label">Inter&eacute;s:</label>
                                                                <input type="text" id="interes" name="interes" class="form-control" autocomplete="off" placeholder="Ingrese inter&eacute;s" value="{{ old('interes') }}">
                                                                <span class="text-danger">@error('interes') {{ $message }} @enderror</span>
                                                            </div> --}}

                                                            <div class="form-group" style="margin-bottom: 0px;">
                                                                {{-- <input type="hidden" id="interes" name="interes" value="{{ old('interes') }}"> --}}
                                                                <input type="checkbox" id="interes_edit" name="interes_edit" value="{{ old('interes_edit') }}">
                                                                <label for="interes_check_edit" class="control-label" style="padding-left: 10px;">¿Inter&eacute;s?</label>
                                                                {{-- <span class="text-danger">@error('interes') {{ $message }} @enderror</span> --}}
                                                            </div>
                                                            <span id="span_interes_edit" class="text-muted">
                                                                NO
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top: 25px;">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">M&iacute;nimo urbano:</label>
                                                                <input type="text" id="minimo_urbano_edit" name="minimo_urbano_edit" class="form-control" autocomplete="off" placeholder="Ingrese m&iacute;nimo urbano" value="{{ old('minimo_urbano_edit') }}">
                                                                <span class="text-danger">@error('minimo_urbano_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">M&iacute;nimo rural:</label>
                                                                <input type="text" id="minimo_rural_edit" name="minimo_rural_edit" class="form-control" autocomplete="off" placeholder="Ingrese m&iacute;nimo rural" value="{{ old('minimo_rural_edit') }}">
                                                                <span class="text-danger">@error('minimo_rural_edit') {{ $message }} @enderror</span>
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
