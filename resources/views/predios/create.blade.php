@extends('theme.default')

@section('content')

@push('scripts')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\PrediosCreateFormRequest', '#create-form'); !!}
    {!! JsValidator::formRequest('App\Http\Requests\PrediosUpdateFormRequest', '#update-form'); !!}
    <script src="{!! asset('theme/js/accounting.min.js') !!}"></script>
    <script src="{!! asset('theme/js/autonumeric.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/jquery.serializeJSON/jquery.serializejson.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/blockUI/jquery.blockUI.js') !!}"></script>
    <script src="{!! asset('theme/js/customjs/controlsite.js') !!}?{{ $current_time }}"></script>
    <script src="{!! asset('theme/js/customjs/predios_forms.js') !!}?{{ $current_time }}"></script>
@endpush
@if(Session::get('tab_current'))
<input type="hidden" id="tab" value="{{ Session::get('tab_current') }}">
@elseif($tab_current)
<input type="hidden" id="tab" value="{{ $tab_current }}">
@endif
<input type="hidden" id="opcion" value='@json($opcion)'>
<input type="hidden" id="max_fecha_descuentos" value='{{ $max_fecha_descuentos }}'>
<input type="hidden" id="fecha_actual" value='{{ $fecha_actual }}'>
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
                                <li id="li-section-bar-1" class="tab-current"><a href="#section-bar-1" class="sticon ti-pulse"><span>Nuevo predio</span></a></li>
                                <li id="li-section-bar-2" class=""><a href="#section-bar-2" class="sticon icon-list"><span>Opciones de predios</span></a></li>
                            </ul>
                        </nav>
                        <div class="content-wrap">
                            <section id="section-bar-1" class="content-current">
                                <div class="panel panel-inverse">
                                    <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Informaci&oacute;n del predio</div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div id="div_verificar_predio" class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Verificar existencia de predio:</label><br />
                                                        <select id="id_predio_edit" class="form-control select2 json pagos" name="id_predio_edit" data-placeholder="C&oacute;digo predio" style="width: 100%">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="div_msg_predio_existe" class="col-lg-8 col-md-7 col-sm-12 col-xs-12" style="display: none;">
                                                    <h2 style="margin-top: 2.5rem;">
                                                        <mark style="border-radius: 0.7rem;">
                                                            Atenci&oacute;n!!! El c&oacute;digo de predio ingresado ya existe en el sistema.
                                                        </mark>
                                                    </h2>
                                                </div>
                                                <div id="div_msg_predio_disponible" class="col-lg-8 col-md-7 col-sm-12 col-xs-12" style="display: none;">
                                                    <h2 style="margin-top: 2.5rem;">
                                                        <mark style="background-color: #d9f4e3; border-radius: 0.7rem;">
                                                            Atenci&oacute;n!!! C&oacute;digo de predio disponible.
                                                        </mark>
                                                    </h2>
                                                </div>
                                            </div>
                                            <form action="{{ route('predios.create_predios') }}" method="post" id="create-form">
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
                                                    <!-- <h3 class="box-title">Informaci&oacute;n de la predio</h3> -->
                                                    <!-- <hr> -->
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                                                            {{-- <input type="text" style="font-size: 170%; letter-spacing: 5px; display: none;" id="codigo_predio" name="codigo_predio" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese c&oacute;digo predio 15/25" value="{{ old('codigo_predio') }}" maxlength="25"> --}}
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo predio:</label>
                                                                <input type="text" style="font-size: 170%; letter-spacing: 5px;" id="codigo_predio" name="codigo_predio" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese c&oacute;digo predio 15/25" value="{{ old('codigo_predio') }}" maxlength="25">
                                                                <span class="text-danger">@error('codigo_predio') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row labels_codigo_predio" style="display: none;">
                                                        <div id="div_tipo" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_15" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Tipo:</label>
                                                                <input type="hidden" id="tipo" name="tipo" value="{{ old('tipo') }}">
                                                                <span id="span_tipo" class="span_predio"></span>
                                                                {{-- <input type="text" id="tipo" name="tipo" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('tipo') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('tipo') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_zona" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Zona:</label>
                                                                <input type="hidden" id="zona" name="zona" value="{{ old('zona') }}">
                                                                <span id="span_zona" class="span_predio"></span>
                                                                {{-- <input type="text" id="zona" name="zona" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('zona') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('zona') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>

                                                        <div id="div_sector" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_15_25" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Sector:</label>
                                                                <input type="hidden" id="sector" name="sector" value="{{ old('sector') }}">
                                                                <span id="span_sector" class="span_predio"></span>
                                                                {{-- <input type="text" id="sector" name="sector" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('sector') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('sector') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>

                                                        <div id="div_comuna" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Comuna:</label>
                                                                <input type="hidden" id="comuna" name="comuna" value="{{ old('comuna') }}">
                                                                <span id="span_comuna" class="span_predio"></span>
                                                                {{-- <input type="text" id="comuna" name="comuna" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('comuna') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('comuna') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_barrio" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Barrio:</label>
                                                                <input type="hidden" id="barrio" name="barrio" value="{{ old('barrio') }}">
                                                                <span id="span_barrio" class="span_predio"></span>
                                                                {{-- <input type="text" id="barrio" name="barrio" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('barrio') }}" maxlength="4"> --}}
                                                                <span class="text-danger">@error('barrio') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>

                                                        <div id="div_manzana" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_15_25" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Mz<span class="codigo_25">/Vda</span>:</label>
                                                                <input type="hidden" id="manzana" name="manzana" value="{{ old('manzana') }}">
                                                                <span id="span_manzana" class="span_predio"></span>
                                                                {{-- <input type="text" id="manzana" name="manzana" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('manzana') }}" maxlength="4"> --}}
                                                                <span class="text-danger">@error('manzana') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>

                                                        <div id="div_predio" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_15" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Predio:</label>
                                                                <input type="hidden" id="predio" name="predio" value="{{ old('predio') }}">
                                                                <span id="span_predio" class="span_predio"></span>
                                                                {{-- <input type="text" id="predio" name="predio" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('predio') }}" maxlength="4"> --}}
                                                                <span class="text-danger">@error('predio') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_terreno" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Terreno:</label>
                                                                <input type="hidden" id="terreno" name="terreno" value="{{ old('terreno') }}">
                                                                <span id="span_terreno" class="span_predio"></span>
                                                                {{-- <input type="text" id="terreno" name="terreno" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('terreno') }}" maxlength="4"> --}}
                                                                <span class="text-danger">@error('terreno') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>

                                                        <div id="div_mejora" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_15" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Mejora:</label>
                                                                <input type="hidden" id="mejora" name="mejora" value="{{ old('mejora') }}">
                                                                <span id="span_mejora" class="span_predio"></span>
                                                                {{-- <input type="text" id="mejora" name="mejora" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('mejora') }}" maxlength="3"> --}}
                                                                <span class="text-danger">@error('mejora') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_condicion" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Cond:</label>
                                                                <input type="hidden" id="condicion" name="condicion" value="{{ old('condicion') }}">
                                                                <span id="span_condicion" class="span_predio"></span>
                                                                {{-- <input type="text" id="condicion" name="condicion" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('condicion') }}" maxlength="1"> --}}
                                                                <span class="text-danger">@error('condicion') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_edificio_torre" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Edi/Tor:</label>
                                                                <input type="hidden" id="edificio_torre" name="edificio_torre" value="{{ old('edificio_torre') }}">
                                                                <span id="span_edificio_torre" class="span_predio"></span>
                                                                {{-- <input type="text" id="edificio_torre" name="edificio_torre" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('edificio_torre') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('edificio_torre') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_piso" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Piso:</label>
                                                                <input type="hidden" id="piso" name="piso" value="{{ old('piso') }}">
                                                                <span id="span_piso" class="span_predio"></span>
                                                                {{-- <input type="text" id="piso" name="piso" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('piso') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('piso') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_propiedad" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Propiedad:</label>
                                                                <input type="hidden" id="propiedad" name="propiedad" value="{{ old('propiedad') }}">
                                                                <span id="span_propiedad" class="span_predio"></span>
                                                                {{-- <input type="text" id="propiedad" name="propiedad" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('propiedad') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('propiedad') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Zona:</label>
                                                                <select id="id_zona" name="id_zona" class="form-control selectpicker show-tick" data-live-search="true" data-size="4" title="Seleccione...">
                                                                    @if(count($zonas) > 0)
                                                                        @foreach($zonas as $zona)
                                                                        <option value="{{ $zona->id }}" {{ old('id_zona') == $zona->id ? 'selected' : '' }}>{{ $zona->descripcion }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <span class="text-danger">@error('id_zona') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="display: none;">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre propietario:</label>
                                                                <input type="text" id="nombre_propietario" name="nombre_propietario" class="form-control" autocomplete="off" value="{{ old('nombre_propietario') }}" maxlength="128">
                                                            </div>
                                                        </div> --}}
                                                        <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Direcci&oacute;n:</label>
                                                                <input type="text" id="direccion" name="direccion" class="form-control" autocomplete="off" placeholder="Ingrese direcci&oacute;n" value="{{ old('direccion') }}" maxlength="128">
                                                                <span class="text-danger">@error('direccion') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">&Aacute;rea metros:</label>
                                                                <input type="text" id="area_metros" name="area_metros" class="form-control" autocomplete="off" placeholder="Ingrese &aacute;rea metros" value="{{ old('area_metros') }}">
                                                                <span class="text-danger">@error('area_metros') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">&Aacute;rea construida:</label>
                                                                <input type="text" id="area_construida" name="area_construida" class="form-control" autocomplete="off" placeholder="Ingrese &aacute;rea construida" value="{{ old('area_construida') }}">
                                                                <span class="text-danger">@error('area_construida') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">&Aacute;rea hect&aacute;reas:</label>
                                                                <input type="text" id="area_hectareas" name="area_hectareas" class="form-control" autocomplete="off" placeholder="Ingrese &aacute;rea hect&aacute;reas" value="{{ old('area_hectareas') }}">
                                                                <span class="text-danger">@error('area_hectareas') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tarifa actual:</label>
                                                                <input type="text" id="tarifa_actual" name="tarifa_actual" class="form-control" autocomplete="off" placeholder="Ingrese tarifa actual" value="{{ old('tarifa_actual') }}">
                                                                <span class="text-danger">@error('tarifa_actual') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Aval&uacute;o:</label>
                                                                <input type="text" id="avaluo" name="avaluo" class="form-control" autocomplete="off" placeholder="Ingrese aval&uacute;o" value="{{ old('avaluo') }}">
                                                                <span class="text-danger">@error('avaluo') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">&Uacute;ltimo a&ntilde;o pago:</label>
                                                                <input type="text" id="ultimo_anio_pago" name="ultimo_anio_pago" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese a&ntilde;o" value="{{ old('ultimo_anio_pago') }}" maxlength="4">
                                                                <span class="text-danger">@error('ultimo_anio_pago') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <hr style="margin-top: 0px;" >
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group" style="margin-bottom: 0px;">
                                                                        <input type="checkbox" id="ind_ley1995" name="ind_ley1995" value="{{ old('ind_ley1995') }}">
                                                                        <label for="ind_ley1995_check" class="control-label" style="padding-left: 10px;">¿Aplica ley 1995?</label>
                                                                    </div>
                                                                    <span id="span_ind_ley1995" class="text-muted">
                                                                        NO
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group" style="margin-bottom: 0px;">
                                                                        <input type="checkbox" id="ind_excento_impuesto" name="ind_excento_impuesto" value="{{ old('ind_excento_impuesto') }}">
                                                                        <label for="ind_excento_impuesto_check" class="control-label" style="padding-left: 10px;">¿Exento impuesto?</label>
                                                                    </div>
                                                                    <span id="span_ind_excento_impuesto" class="text-muted">
                                                                        NO
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group" style="margin-bottom: 0px;">
                                                                        <input type="checkbox" id="ind_plusvalia" name="ind_plusvalia" value="{{ old('ind_plusvalia') }}">
                                                                        <label for="ind_plusvalia_check" class="control-label" style="padding-left: 10px;">¿Plusvalia?</label>
                                                                    </div>
                                                                    <span id="span_ind_plusvalia" class="text-muted">
                                                                        NO
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group" style="margin-bottom: 0px;">
                                                                        <input type="checkbox" id="ind_invalidez" name="ind_invalidez" value="{{ old('ind_invalidez') }}">
                                                                        <label for="ind_invalidez_check" class="control-label" style="padding-left: 10px;">¿Discapacidad?</label>
                                                                    </div>
                                                                    <span id="span_ind_invalidez" class="text-muted">
                                                                        NO
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <hr style="margin-bottom: 0px;" >
                                                    </div>
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
                                            @if(isset($predios))
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
                                                {{-- <div class="row" style="{{ !$batchs ? print('min-height: 220px;') : ''}}"> --}}
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Buscar predio:</label><br />
                                                            <select id="id_predio" class="form-control select2 json pagos" name="id_predio" data-placeholder="C&oacute;digo, propietario o direcci&oacute;n..." style="width: 100%">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-offset-2 col-lg-6 col-md-offset-2 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="btn-group">
                                                            <label class="control-label">Otras operaciones...</label><br />
                                                            <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-outline dropdown-toggle waves-effect waves-light batch_element" style="height: 38px;{{ $batchs ? print('display: none;') : ''}}" type="button"> <i class="fa fa-gears m-r-5"></i> <span class="caret"></span></button>
                                                            <ul role="menu" class="dropdown-menu batch_element" style="{{ $batchs ? print('display: none;') : ''}}">
                                                                <li style="display: none;"><a id="btn_batch" href="#" data-toggle="modal" data-target="#modal-batch" data-backdrop="static" data-keyboard="false">Procesar c&aacute;lculo batch</a></li>
                                                                <li class="divider"></li>
                                                                <li><a id="btn_cartera" href="#">Reporte de cartera</a></li>
                                                            </ul>
                                                            <p class="text-muted batch_message" style="text-align: justify;{{ !$batchs ? print('display: none;') : ''}}">
                                                                <code style="width: 100%">
                                                                    Se ha detectado un proceso de c&aacute;lculo predial batch en curso.<br />
                                                                    Se recomienda <b>no realizar</b> acciones adicionales hasta que el proceso en ejecuci&oacute;n haya finalizado.
                                                                </code><br />
                                                                <small><b>Presione la tecla</small> <mark>F5</mark> <small>para actualizar los conteos.</b></small>
                                                            </p>
                                                            <div class="row batch_message" style="text-align: justify;{{ !$batchs ? print('display: none;') : ''}}">
                                                                <div class="col-lg-12">
                                                                    <table class="table table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Total por calular</th>
                                                                                <th>Procesados</th>
                                                                                <th>Restantes</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @if($batchs)
                                                                            <tr>
                                                                                <td>{{ $batchs->por_calcular }}</td>
                                                                                <td>{{ $batchs->calculados }}</td>
                                                                                <td>{{ $batchs->por_calcular - $batchs->calculados }}</td>
                                                                            </tr>
                                                                            @else
                                                                            <tr>
                                                                                <td id="td_por_calcular"></td>
                                                                                <td id="td_procesados"></td>
                                                                                <td id="td_restantes"></td>
                                                                            </tr>
                                                                            @endif
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="div_edit_predio" class="row" style="display: none;">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <h2>Opciones de predio</h2>
                                                        <table id="myTable" class="table table-hover table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="cell_center" style="width: 7%;">C&oacute;digo predio</th>
                                                                    <th class="cell_center" style="width: 7%;">Direcci&oacute;n</th>
                                                                    <th class="cell_center" style="width: 7%;">Propietario/s</th>
                                                                    <th class="cell_center" style="width: 10%;">Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                {{-- <div class="pagination-blobk">
                                                    {{ $predios->links('layouts.paginationlinks') }}
                                                </div> --}}
                                                <form id="form_delete" action="{{ route('predios.delete_predios') }}" method="post" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" id="input_delete" name="input_delete">
                                                </form>
                                                <form id="form_prescribe" action="{{ route('predios.prescribe_predios') }}" method="post" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" id="input_prescribe" name="input_prescribe">
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div id="div_edit_form" class="panel panel-info" style="display: none; margin-bottom: 0px">
                                    <div class="panel-heading">
                                        <i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Actualizar informaci&oacute;n del predio
                                        {{-- <small class="text-danger pull-right" style="font-weight: bold; display: none; color: rgb(12, 130, 188); font-size: 20px;">Predio prescrito hasta <span id="span_prescribe_hasta"></span></small> --}}
                                    </div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            @if($opcion->resolucion_edita == 1)
                                            {{--
                                                Aqui se establecen todos los campos que se desee validar antes de enviar el formulario.
                                                Esto aplica solo para los formularios que necesitan una resolucion para edicion.
                                            --}}
                                            {{-- Campos --}}
                                            {{-- <input class="resolucion_validate_field_level-update-form" type="hidden" field="codigo_predio_edit" value="" /> --}}

                                            {{--
                                                Aqui se establece el id del formulario que se desea validar.
                                                Esto aplica solo para los formularios que necesitan una resolucion para edicion.
                                                Si se usa validacion de formulario no se deben usar campos aislados.
                                                En caso de que se use validacion de campos y formulario, se ignora los campos aislados
                                                y se hace una validacion completa del formulario.
                                                Cada campo dentro del formulario necesita llevar la clase res-validate.
                                            --}}
                                            {{-- Formulario --}}
                                            <input class="resolucion_validate_form_level-update-form" type="hidden" value="update-form" />
                                            @endif
                                            <form action="{{ route('predios.update_predios') }}" method="post" id="update-form" desc-to-resolucion-modal="predio">
                                                @csrf

                                                <div class="form-body">
                                                    <input type="hidden" id="id_edit" name="id_edit" value="{{ old('id_edit') }}">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo predio:</label>
                                                                <input type="text" style="font-size: 170%; letter-spacing: 5px;" id="codigo_predio_edit" name="codigo_predio_edit" class="form-control onlyNumbers res-validate" autocomplete="off" placeholder="Ingrese c&oacute;digo predio 15/25" value="{{ old('codigo_predio_edit') }}" maxlength="25">
                                                                <span class="text-danger">@error('codigo_predio_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div id="div_tipo_edit" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_15_edit" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Tipo:</label>
                                                                <input type="hidden" id="tipo_edit" name="tipo_edit" value="{{ old('tipo_edit') }}">
                                                                <span id="span_tipo_edit" class="span_predio"></span>
                                                                {{-- <input type="text" id="tipo_edit" name="tipo_edit" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('tipo_edit') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('tipo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_zona_edit" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25_edit" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Zona:</label>
                                                                <input type="hidden" id="zona_edit" name="zona_edit" value="{{ old('zona_edit') }}">
                                                                <span id="span_zona_edit" class="span_predio"></span>
                                                                {{-- <input type="text" id="zona_edit" name="zona_edit" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('zona_edit') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('zona_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>

                                                        <div id="div_sector_edit" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_15_25_edit" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Sector:</label>
                                                                <input type="hidden" id="sector_edit" name="sector_edit" value="{{ old('sector_edit') }}">
                                                                <span id="span_sector_edit" class="span_predio"></span>
                                                                {{-- <input type="text" id="sector_edit" name="sector_edit" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('sector_edit') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('sector_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>

                                                        <div id="div_comuna_edit" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25_edit" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Comuna:</label>
                                                                <input type="hidden" id="comuna_edit" name="comuna_edit" value="{{ old('comuna_edit') }}">
                                                                <span id="span_comuna_edit" class="span_predio"></span>
                                                                {{-- <input type="text" id="comuna_edit" name="comuna_edit" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('comuna_edit') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('comuna_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_barrio_edit" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25_edit" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Barrio:</label>
                                                                <input type="hidden" id="barrio_edit" name="barrio_edit" value="{{ old('barrio_edit') }}">
                                                                <span id="span_barrio_edit" class="span_predio"></span>
                                                                {{-- <input type="text" id="barrio_edit" name="barrio_edit" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('barrio_edit') }}" maxlength="4"> --}}
                                                                <span class="text-danger">@error('barrio_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>

                                                        <div id="div_manzana_edit" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_15_25_edit" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Mz<span class="codigo_25_edit">/Vda</span>:</label>
                                                                <input type="hidden" id="manzana_edit" name="manzana_edit" value="{{ old('manzana_edit') }}">
                                                                <span id="span_manzana_edit" class="span_predio"></span>
                                                                {{-- <input type="text" id="manzana_edit" name="manzana_edit" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('manzana_edit') }}" maxlength="4"> --}}
                                                                <span class="text-danger">@error('manzana_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>

                                                        <div id="div_predio_edit" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_15_edit" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Predio:</label>
                                                                <input type="hidden" id="predio_edit" name="predio_edit" value="{{ old('predio_edit') }}">
                                                                <span id="span_predio_edit" class="span_predio"></span>
                                                                {{-- <input type="text" id="predio_edit" name="predio_edit" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('predio_edit') }}" maxlength="4"> --}}
                                                                <span class="text-danger">@error('predio_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_terreno_edit" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25_edit" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Terreno:</label>
                                                                <input type="hidden" id="terreno_edit" name="terreno_edit" value="{{ old('terreno_edit') }}">
                                                                <span id="span_terreno_edit" class="span_predio"></span>
                                                                {{-- <input type="text" id="terreno_edit" name="terreno_edit" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('terreno_edit') }}" maxlength="4"> --}}
                                                                <span class="text-danger">@error('terreno_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>

                                                        <div id="div_mejora_edit" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_15_edit" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Mejora:</label>
                                                                <input type="hidden" id="mejora_edit" name="mejora_edit" value="{{ old('mejora_edit') }}">
                                                                <span id="span_mejora_edit" class="span_predio"></span>
                                                                {{-- <input type="text" id="mejora_edit" name="mejora_edit" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('mejora_edit') }}" maxlength="3"> --}}
                                                                <span class="text-danger">@error('mejora_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_condicion_edit" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25_edit" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Cond:</label>
                                                                <input type="hidden" id="condicion_edit" name="condicion_edit" value="{{ old('condicion_edit') }}">
                                                                <span id="span_condicion_edit" class="span_predio"></span>
                                                                {{-- <input type="text" id="condicion_edit" name="condicion_edit" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('condicion_edit') }}" maxlength="1"> --}}
                                                                <span class="text-danger">@error('condicion_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_edificio_torre_edit" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25_edit" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Edi/Tor:</label>
                                                                <input type="hidden" id="edificio_torre_edit" name="edificio_torre_edit" value="{{ old('edificio_torre_edit') }}">
                                                                <span id="span_edificio_torre_edit" class="span_predio"></span>
                                                                {{-- <input type="text" id="edificio_torre_edit" name="edificio_torre_edit" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('edificio_torre_edit') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('edificio_torre_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_piso_edit" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25_edit" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Piso:</label>
                                                                <input type="hidden" id="piso_edit" name="piso_edit" value="{{ old('piso_edit') }}">
                                                                <span id="span_piso_edit" class="span_predio"></span>
                                                                {{-- <input type="text" id="piso_edit" name="piso_edit" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('piso_edit') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('piso_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_propiedad_edit" class="col-lg-1 col-md-1 col-sm-2 col-xs-6 codigo_25_edit" style="opacity: 0;">
                                                            <div class="form-group">
                                                                <label class="control-label">Propiedad:</label>
                                                                <input type="hidden" id="propiedad_edit" name="propiedad_edit" value="{{ old('propiedad_edit') }}">
                                                                <span id="span_propiedad_edit" class="span_predio"></span>
                                                                {{-- <input type="text" id="propiedad_edit" name="propiedad_edit" class="form-control nofocus" autocomplete="off" placeholder="" value="{{ old('propiedad_edit') }}" maxlength="2"> --}}
                                                                <span class="text-danger">@error('propiedad_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Zona:</label>
                                                                <select id="id_zona_edit" name="id_zona_edit" class="form-control selectpicker show-tick res-validate" data-live-search="true" data-size="4" title="Seleccione...">
                                                                    @if(count($zonas) > 0)
                                                                        @foreach($zonas as $zona)
                                                                        <option value="{{ $zona->id }}" {{ old('id_zona_edit') == $zona->id ? 'selected' : '' }}>{{ $zona->descripcion }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <span class="text-danger">@error('id_zona_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Direcci&oacute;n:</label>
                                                                <input type="text" id="direccion_edit" name="direccion_edit" class="form-control res-validate" autocomplete="off" placeholder="Ingrese direcci&oacute;n" value="{{ old('direccion_edit') }}" maxlength="128">
                                                                <span class="text-danger">@error('direccion_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">&Aacute;rea metros:</label>
                                                                <input type="text" id="area_metros_edit" name="area_metros_edit" class="form-control res-validate" autocomplete="off" placeholder="Ingrese &aacute;rea metros" value="{{ old('area_metros_edit') }}">
                                                                <span class="text-danger">@error('area_metros_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">&Aacute;rea construida:</label>
                                                                <input type="text" id="area_construida_edit" name="area_construida_edit" class="form-control res-validate" autocomplete="off" placeholder="Ingrese &aacute;rea construida" value="{{ old('area_construida_edit') }}">
                                                                <span class="text-danger">@error('area_construida_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">&Aacute;rea hect&aacute;reas:</label>
                                                                <input type="text" id="area_hectareas_edit" name="area_hectareas_edit" class="form-control res-validate" autocomplete="off" placeholder="Ingrese &aacute;rea hect&aacute;reas" value="{{ old('area_hectareas_edit') }}">
                                                                <span class="text-danger">@error('area_hectareas_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tarifa actual:</label>
                                                                <input type="text" id="tarifa_actual_edit" name="tarifa_actual_edit" class="form-control res-validate" autocomplete="off" placeholder="Ingrese tarifa actual" value="{{ old('tarifa_actual_edit') }}" readonly style="background-color: white;">
                                                                <span class="text-danger">@error('tarifa_actual_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Aval&uacute;o:</label>
                                                                <input type="text" id="avaluo_edit" name="avaluo_edit" class="form-control res-validate" autocomplete="off" placeholder="Ingrese aval&uacute;o" value="{{ old('avaluo_edit') }}">
                                                                <span class="text-danger">@error('avaluo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">&Uacute;ltimo a&ntilde;o pago:</label>
                                                                <input type="text" id="ultimo_anio_pago_edit" name="ultimo_anio_pago_edit" class="form-control onlyNumbers res-validate" autocomplete="off" placeholder="Ingrese a&ntilde;o" value="{{ old('ultimo_anio_pago_edit') }}" maxlength="4">
                                                                <span class="text-danger">@error('ultimo_anio_pago_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <hr style="margin-top: 0px;" >
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group" style="margin-bottom: 0px;">
                                                                        <input type="checkbox" id="ind_ley1995_edit" name="ind_ley1995_edit" class="res-validate" value="{{ old('ind_ley1995_edit') }}">
                                                                        <label for="ind_ley1995_check_edit" class="control-label" style="padding-left: 10px;">¿Aplica ley 1995?</label>
                                                                    </div>
                                                                    <span id="span_ind_ley1995_edit" class="text-muted">
                                                                        NO
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group" style="margin-bottom: 0px;">
                                                                        <input type="checkbox" id="ind_excento_impuesto_edit" name="ind_excento_impuesto_edit" value="{{ old('ind_excento_impuesto_edit') }}">
                                                                        <label for="ind_excento_impuesto_check_edit" class="control-label" style="padding-left: 10px;">¿Exento impuesto?</label>
                                                                    </div>
                                                                    <span id="span_ind_excento_impuesto_edit" class="text-muted">
                                                                        NO
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group" style="margin-bottom: 0px;">
                                                                        <input type="checkbox" id="ind_plusvalia_edit" name="ind_plusvalia_edit" value="{{ old('ind_plusvalia_edit') }}">
                                                                        <label for="ind_plusvalia_check_edit" class="control-label" style="padding-left: 10px;">¿Plusvalia?</label>
                                                                    </div>
                                                                    <span id="span_ind_plusvalia_edit" class="text-muted">
                                                                        NO
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group" style="margin-bottom: 0px;">
                                                                        <input type="checkbox" id="ind_invalidez_edit" name="ind_invalidez_edit" value="{{ old('ind_invalidez_edit') }}">
                                                                        <label for="ind_invalidez_check_edit" class="control-label" style="padding-left: 10px;">¿Discapacidad?</label>
                                                                    </div>
                                                                    <span id="span_ind_invalidez_edit" class="text-muted">
                                                                        NO
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <hr style="margin-bottom: 0px;" >
                                                    </div>
                                                </div>
                                                <div class="form-actions m-t-20">
                                                    <button id="btn_save_edit" type="button" class="btn btn-info"> <i class="fa fa-save"></i> Actualizar informaci&oacute;n</button>
                                                    <button id="btn_cancel_edit" type="button" class="btn btn-default"> <i class="fa fa-thumbs-down"></i> Cancelar</button>
                                                </div>
                                            </form>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                                                    <hr />
                                                    <h3 class="box-title">Informaci&oacute;n adicional - <span>&Uacute;ltimo pago</h3>
                                                    <blockquote style="font-size: 97%; border-left: 5px solid #c9f018 !important;">
                                                        <form id="form-predios-datos-pagos">
                                                            <div class="form-body">
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Banco:</label>
                                                                            <select id="id_banco" name="id_banco" class="form-control selectpicker-noval show-tick" data-live-search="true" data-size="4" title="Sin informaci&oacute;n..." readonly="true" disabled="disabled">
                                                                                @if(count($bancos) > 0)
                                                                                    @foreach($bancos as $banco)
                                                                                    <option value="{{ $banco->id }}" {{ old('id_banco') == $banco->id ? 'selected' : '' }}>{{ $banco->codigo }} - {{ $banco->nombre }} ({{ $banco->asobancaria }})</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Factura pago:</label>
                                                                            <input type="text" id="factura_pago" name="factura_pago" class="form-control" autocomplete="off" placeholder="-" value="" maxlength="15" readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">&Uacute;ltimo a&ntilde;o pago:</label>
                                                                            <input type="text" id="ultimo_anio" name="ultimo_anio" class="form-control onlyNumbers" autocomplete="off" placeholder="-" value="" maxlength="4" readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Valor pago:</label>
                                                                            <input type="text" id="valor_pago" name="valor_pago" class="form-control" autocomplete="off" placeholder="-" value="" readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Fecha pago:</label>
                                                                            <input type="text" id="fecha_pago" name="fecha_pago" class="form-control" autocomplete="off" placeholder="-" value="" readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                                                        <div class="form-group" style="margin-bottom: 0px;">
                                                                            <label for="acuerdo_pago_check" class="control-label" style="display: block;">¿Acuerdo de pago?</label>
                                                                            <input type="checkbox" id="acuerdo_pago" name="acuerdo_pago" value="">
                                                                            <span id="span_acuerdo_pago" class="text-muted" style="padding-left: 10px;">NO</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </blockquote>
                                                </div>
                                            </div>
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

@section('buttons')
<li class="mega-dropdown buttonTareas">
    <a class="waves-effect waves-light" data-tooltip="tooltip" data-placement="bottom" data-toggle="modal" data-target="#modal-datos-basicos" data-backdrop="static" data-keyboard="false" href="#" title="Datos b&aacute;sicos predios">
        <span class="hidden-xs">DBP</span> <i class="icon-home"></i>
    </a>
</li>
<li class="mega-dropdown buttonTareas">
    <a class="waves-effect waves-light" data-tooltip="tooltip" data-placement="bottom" data-toggle="modal" data-target="#modal-datos-propietarios" data-backdrop="static" data-keyboard="false" href="#" title="Datos propietarios">
        <span class="hidden-xs">DP</span> <i class="icon-people"></i>
    </a>
</li>
{{-- <li class="mega-dropdown buttonTareas">
    <a class="waves-effect waves-light" data-tooltip="tooltip" data-placement="bottom" data-toggle="modal" data-target="#modal-datos-calculo" data-backdrop="static" data-keyboard="false" href="#" title="Datos c&aacute;lculo">
        <span class="hidden-xs">DC</span> <i class="icon-calculator"></i>
    </a>
</li> --}}
{{-- <li class="mega-dropdown buttonTareas">
    <a class="waves-effect waves-light" data-tooltip="tooltip" data-placement="bottom" data-toggle="modal" data-target="#modal-datos-pagos" data-backdrop="static" data-keyboard="false" href="#" title="Datos pagos">
        <span class="hidden-xs">DPA</span> <i class="icon-wallet"></i>
    </a>
</li> --}}
{{-- <li class="mega-dropdown buttonTareas">
    <a class="waves-effect waves-light" data-tooltip="tooltip" data-placement="bottom" data-toggle="modal" data-target="#modal-datos-acuerdo-pago" data-backdrop="static" data-keyboard="false" href="#" title="Datos acuerdos de pago">
        <span class="hidden-xs">DAP</span> <i class="icon-book-open"></i>
    </a>
</li> --}}
<li class="mega-dropdown buttonTareas">
    <a class="waves-effect waves-light" data-tooltip="tooltip" data-placement="bottom" data-toggle="modal" data-target="#modal-datos-abonos" data-backdrop="static" data-keyboard="false" href="#" title="Datos abonos">
        <span class="hidden-xs">DA</span> <i class="icon-flag"></i>
    </a>
</li>
<li class="mega-dropdown buttonTareas">
    <a class="waves-effect waves-light" data-tooltip="tooltip" data-placement="bottom" data-toggle="modal" data-target="#modal-datos-procesos-historicos" data-backdrop="static" data-keyboard="false" href="#" title="Procesos e hist&oacute;ricos">
        <span class="hidden-xs">P&H</span> <i class="icon-layers"></i>
    </a>
</li>
<li class="mega-dropdown buttonTareas">
    <a class="waves-effect waves-light" data-tooltip="tooltip" data-placement="bottom" data-toggle="modal" data-target="#modal-datos-estado-cuenta" data-backdrop="static" data-keyboard="false" href="#" title="Estado de cuenta">
        <span class="hidden-xs">EC</span> <i class="icon-calculator"></i>
    </a>
</li>
@endsection

@section('modales')
<div id="modal-prescripciones" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-prescripciones-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-prescripciones-label">Prescribir predio</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-predios-prescripcion">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <label class="control-label">Desde...</label>
                                            <input type="text" id="prescribe_desde_modal" name="prescribe_desde_modal" class="form-control onlyNumbers" autocomplete="off" placeholder="A&ntilde;o" value="{{ old('prescribe_desde_modal') }}" maxlength="4" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group" style="margin-top: 0px;">
                                            <label class="control-label">Hasta...</label>
                                            <select id="prescribe_hasta_modal" name="prescribe_hasta_modal" class="form-control selectpicker" data-size="3" title="A&ntilde;o..." data-container="#modal-prescripciones" style="width: 100%;">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span id="span_prescribir" class="text-info">&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="save_prescripcion" type="button" class="btn btn-info"> <i class="fa fa-save"></i> Prescribir</button>
                <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="modal-impresion-factura" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-impresion-factura-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm" style="width: 24%;">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-impresion-factura-label">Generaci&oacute;n de factura de cobro impuesto predial.</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-predios-impresion-factura">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 0px; text-align: center;">
                                            {{-- <label class="control-label">Generaci&oacute;n de factura de cobro impuesto predial.</label> --}}
                                            <div id="div_propietario_facturar" style="width: 100%; text-align: left; padding-bottom: 20px;">
                                                <label style="width: 100%;" class="control-label">Propietario:</label>
                                                <select id="propietario_facturar" name="propietario_facturar" class="form-control" data-size="4" title="Seleccione propietario..." data-container="#modal-impresion-factura" style="width: 100%;">
                                                </select>
                                            </div>
                                            <select id="ultimo_anio_facturar" name="ultimo_anio_facturar" class="form-control" data-size="4" title="Seleccione a&ntilde;o..." data-container="#modal-impresion-factura" style="width: 100%">
                                            </select>
                                        </div>
                                    </div>
                                    <div id="div_anios_factura" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none; padding-top: 10px;">
                                        <span style="color: #0ca65c; font-weight: 400;">A&ntilde;os cubiertos en la misma factura</span>
                                        <hr style="margin-top: 2px; margin-bottom: 0px;">
                                        <div class="row" id="lista_anios_factura">
                                        </div>
                                        <hr style="margin-top: 0px; margin-bottom: 2px;">
                                        <span class="blink_me" style="color: #0ca65c; font-weight: 400; font-size: 85%; margin-bottom: 2px;">
                                            Por favor deseleccione los que a&ntilde;os NO desea que aparezcan en la factura actual.
                                            {{-- <br /><u>Recuerde que no se permiten selecciones de a&ntilde;os no consecutivos.</u> --}}
                                        </span>
                                    </div>
                                    <div id="div_vigencias_impresion" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 0px; margin-top: 10px;">
                                            <label for="tipo_factura_check" class="control-label" style="display: block;">¿Facturar vigencias especificas?</label>
                                            <input type="checkbox" id="tipo_factura" name="tipo_factura" value="{{ old('tipo_factura') }}">
                                            <span id="span_tipo_factura" class="text-muted" style="padding-left: 10px;">NO</span>
                                        </div>
                                    </div>
                                    <div id="div_fecha_max_pago" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 10px;">
                                        <div class="form-group" style="margin-bottom: 0px; text-align: center;">
                                            <label class="control-label">Fecha m&aacute;xima de pago</label>
                                            <input type="text" id="fecha_max_pago" name="fecha_max_pago" class="form-control datepicker" autocomplete="off" placeholder="Fecha m&aacute;xima" value="{{ old('fecha_max_pago') }}" style="width: 100%;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button id="generate_factura_definitiva" type="button" class="btn btn-info btn_pdf btn_ver_factura"> <i class="fa fa-file-text"></i> Ver definitiva</button>
                <button id="generate_factura_temporal" type="button" class="btn btn-inverse btn_pdf btn_ver_factura"> <i class="fa fa-file-text-o"></i> Vista previa  </button>
                <button id="btn_modificar_anios" type="button" class="btn btn-success" style="display: none;"> <i class="fa fa-save"></i> Actualizar factura</button>
                <button type="button" class="btn btn-danger btn_pdf" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
{{-- <div id="modal-impresion-factura" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-impresion-factura-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
                <h4 class="modal-title" id="modal-impresion-factura-label">Atenci&oacute;n!</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-predios-impresion-factura">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 0px; text-align: center;">
                                            <label class="control-label">Generaci&oacute;n de factura de cobro impuesto predial.</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">A&ntilde;o:</label>
                                            <select id="ultimo_anio_facturar" name="ultimo_anio_facturar" class="form-control" data-size="4" title="Seleccione a&ntilde;o..." data-container="#modal-impresion-factura" style="width: 100%">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Cuotas:</label>
                                            <input type="text" id="cuotas_factura" name="cuotas_factura" class="form-control onlyNumbers" value="0" autocomplete="off" placeholder="Cuotas" value="{{ old('cuotas_factura') }}" maxlength="2" />
                                        </div>
                                    </div>
                                </div>
                                <div id="div_fecha_pago_factura" class="row" style="margin-bottom: 0px;">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Fecha pago:</label>
                                            <input type="text" id="fecha_pago_factura" name="fecha_pago_factura" class="form-control datepicker" autocomplete="off" placeholder="Fecha pago" value="{{ old('fecha_pago_factura') }}" style="width: 100%;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button id="generate_factura_definitiva" type="button" class="btn btn-info btn_pdf"> <i class="fa fa-file-text"></i> Ver definitiva</button>
                <button id="generate_factura_temporal" type="button" class="btn btn-inverse btn_pdf"> <i class="fa fa-file-text-o"></i> Vista previa  </button>
                <button type="button" class="btn btn-danger btn_pdf" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div> --}}
<div id="modal-impresion-paz" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-impresion-paz-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-impresion-paz-label">Atenci&oacute;n!</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-predios-impresion-paz">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" style="width: 100%; text-align: center;">Generaci&oacute;n certificado de paz y salvo - impuesto predial.</label>
                                            <label class="control-label">Destino:</label>
                                            <input type="text" id="destino_paz" name="destino_paz" class="form-control" autocomplete="off" placeholder="Ingrese nombre destino" value="{{ old('destino_paz') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Fecha validez:</label>
                                            <input type="text" id="fecha_paz" name="fecha_paz" class="form-control datepicker" autocomplete="off" placeholder="Fecha validez" value="{{ old('fecha_paz') }}" style="width: 100%;">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Valor:</label>
                                            <input type="text" id="valor_paz" name="valor_paz" class="form-control" autocomplete="off" placeholder="Valor en pesos" value="{{ old('valor_paz') }}" style="width: 100%;">
                                        </div>
                                    </div>
                                </div>
                                <div id="div_message_plusvalia" class="row" style="display: none;">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="color: #df1c1c; font-weight: 400;">
                                        <span style="color: #eca00f;"><i class="fa fa-warning"></i></span> El predio seleccionado presenta un indicador de plusvalia activo. Por favor, no olvide realizar el cobro pertinente.
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="generate_paz" type="button" class="btn btn-info btn_pdf"> <i class="fa fa-download"></i> Generar</button>
                <button type="button" class="btn btn-danger btn_pdf" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
{{-- <div id="modal-avaluo" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-avaluo-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-avaluo-label">Aval&uacute;o de predio</h4>
            </div>
            <div class="modal-body">
                <table id="avaluosTable" class="table table-hover table-condensed table-striped table-bordered" style="margin-bottom: 10px;"></table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div> --}}
<div id="modal-datos-basicos" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-datos-basicos-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-datos-basicos-label">Informaci&oacute;n adicional - <span>Datos b&aacute;sicos predios</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-predios-datos-basicos">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Matr&iacute;cula inmobiliaria:</label>
                                            <input type="text" id="matricula_inmobiliaria" name="matricula_inmobiliaria" class="form-control" autocomplete="off" placeholder="Ingrese matricula inmobiliaria" value="{{ old('matricula_inmobiliaria') }}" maxlength="15">
                                            {{-- <span class="text-danger">@error('matricula_inmobiliaria') {{ $message }} @enderror</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Tipo predio:</label>
                                            <select id="id_tipo_predio" name="id_tipo_predio" class="form-control selectpicker-noval show-tick" data-live-search="true" data-size="4" title="Seleccione..." data-container="#modal-datos-basicos">
                                                @if(count($tipos_predio) > 0)
                                                    @foreach($tipos_predio as $tipo_predio)
                                                    <option value="{{ $tipo_predio->id }}" {{ old('id_tipo_predio') == $tipo_predio->id ? 'selected' : '' }}>{{ $tipo_predio->nombre }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Clase predio:</label>
                                            <select id="id_clase_predio" name="id_clase_predio" class="form-control selectpicker-noval show-tick" data-live-search="true" data-size="4" title="Seleccione..." data-container="#modal-datos-basicos">
                                                @if(count($clases_predio) > 0)
                                                    @foreach($clases_predio as $clase_predio)
                                                    <option value="{{ $clase_predio->id }}" {{ old('id_clase_predio') == $clase_predio->id ? 'selected' : '' }}>{{ $clase_predio->nombre }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Clase mutaci&oacute;n:</label>
                                            <select id="id_clase_mutacion" name="id_clase_mutacion" class="form-control selectpicker-noval show-tick" data-live-search="true" data-size="4" title="Seleccione..." data-container="#modal-datos-basicos">
                                                @if(count($clases_mutacion) > 0)
                                                    @foreach($clases_mutacion as $clase_mutacion)
                                                    <option value="{{ $clase_mutacion->id }}" {{ old('id_clase_mutacion') == $clase_mutacion->id ? 'selected' : '' }}>{{ $clase_mutacion->nombre }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Aval&uacute;o presente a&ntilde;o:</label>
                                            <input type="text" id="avaluo_presente_anio" name="avaluo_presente_anio" class="form-control" autocomplete="off" placeholder="Ingrese aval&uacute;o presente a&ntilde;o" value="{{ old('avaluo_presente_anio') }}" maxlength="15">
                                            {{-- <span class="text-danger">@error('avaluo_presente_anio') {{ $message }} @enderror</span> --}}
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <label for="excento_impuesto_check" class="control-label" style="display: block;">¿Excento impuesto?</label>
                                            <input type="checkbox" id="excento_impuesto" name="excento_impuesto" value="{{ old('excento_impuesto') }}">
                                            <span id="span_excento_impuesto" class="text-muted" style="padding-left: 10px;">NO</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <label for="predio_incautado_check" class="control-label" style="display: block;">¿Predio incautado?</label>
                                            <input type="checkbox" id="predio_incautado" name="predio_incautado" value="{{ old('predio_incautado') }}">
                                            <span id="span_predio_incautado" class="text-muted" style="padding-left: 10px;">NO</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <label for="aplica_ley44_check" class="control-label" style="display: block;">¿Aplica ley 44?</label>
                                            <input type="checkbox" id="aplica_ley44" name="aplica_ley44" value="{{ old('aplica_ley44') }}">
                                            <span id="span_aplica_ley44" class="text-muted" style="padding-left: 10px;">NO</span>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                 </div>
            </div>
            <div class="modal-footer">
                <button id="save_db" type="button" class="btn btn-info"> <i class="fa fa-save"></i> Guardar informaci&oacute;n</button>
                <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="modal-datos-propietarios" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-datos-propietarios-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-datos-propietarios-label">Informaci&oacute;n adicional - <span>Datos propietarios</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-predios-datos-propietarios">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-5 col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label">N&uacute;mero:</label><br />
                                            <input type="hidden" id="jerarquia" name="jerarquia" value="001" />
                                            <div id="div_jerarquia" style="cursor: pointer;">
                                                <span id="span_jerarquia" class="text-muted" style="font-size: 180%; font-weight: bold;">001</span>
                                                <span id="span_de_jererquia" class="text-muted"></span>
                                            </div>
                                            <div id="div_set_jerarquia" style="display: none;">
                                                <div class="input-group">
                                                    <input type="text" class="form-control onlyNumbers" placeholder="Jerarquia" id="val_jerarquia" value="" maxlength="3">
                                                    <span class="input-group-btn">
                                                         <button id="save_jerarquia" class="btn btn-success" type="button"><i class="fa fa-check"></i></button>
                                                    </span>
                                                 </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-7 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Identificaci&oacute;n:</label>
                                            <input type="text" id="identificacion" name="identificacion" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese identificaci&oacute;n" value="{{ old('identificacion') }}" maxlength="30">
                                            {{-- <span class="text-danger">@error('identificacion') {{ $message }} @enderror</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Nombre:</label>
                                            <input type="text" id="nombre" name="nombre" class="form-control" autocomplete="off" placeholder="Ingrese nombre" value="{{ old('nombre') }}" maxlength="128">
                                            {{-- <span class="text-danger">@error('nombre') {{ $message }} @enderror</span> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <label class="control-label">Direcci&oacute;n:</label>
                                            <input type="text" id="direccion" name="direccion" class="form-control" autocomplete="off" placeholder="Ingrese direcci&oacute;n" value="{{ old('direccion') }}" maxlength="128">
                                            {{-- <span class="text-danger">@error('direccion') {{ $message }} @enderror</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <label class="control-label">Correo electr&oacute;nico:</label>
                                            <input type="text" id="correo_electronico" name="correo_electronico" class="form-control" autocomplete="off" placeholder="Ingrese correo electr&oacute;nico" value="{{ old('correo_electronico') }}" maxlength="128">
                                            {{-- <span class="text-danger">@error('correo_electronico') {{ $message }} @enderror</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6" style="font-size: 80%; display: none;">
                                        <span id="text_page_propietarios" class="text-muted info_propietarios">Edici&oacute;n<br />P&aacute;gina: 1</span><br />
                                        <span id="text_row_propietarios" class="text-muted info_propietarios">Fila: 1</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="divPropietariosTable" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none;">
                        <h2>Lista de propietarios</h2>
                        <table id="propietariosTable" class="table table-hover table-condensed table-striped table-bordered" style="margin-bottom: 10px;"></table>
                    </div>
                 </div>
            </div>
            <div class="modal-footer">
                <button id="new_dp" type="button" class="btn btn-success pull-left control_propietarios" style="display: none;"> <i class="fa fa-cogs"></i> Nuevo</button>
                <button id="cancel_dp" type="button" class="btn btn-default pull-left" style="display: none;"> <i class="fa fa-cogs"></i> Cancelar</button>
                {{-- <button id="prev_dp" type="button" class="btn btn-default pull-left control_propietarios" style="display: none;"> <i class="fa fa-angle-double-left"></i> Anterior</button> --}}
                {{-- <button id="next_dp" type="button" class="btn btn-default pull-left control_propietarios" style="display: none;"> <i class="fa fa-angle-double-right"></i> Siguiente</button> --}}
                <button id="save_dp" type="button" class="btn btn-info"> <i class="fa fa-save"></i> Guardar informaci&oacute;n</button>
                <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
{{-- <div id="modal-datos-calculo" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-datos-calculo-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-datos-calculo-label">Informaci&oacute;n adicional - <span>Datos c&aacute;lculo</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-predios-datos-calculo">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Estrato:</label>
                                            <input type="text" id="estrato" name="estrato" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese estrato" value="{{ old('estrato') }}" maxlength="3">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Destino econ&oacute;mico:</label>
                                            <input type="text" id="destino_economico" name="destino_economico" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese destino econ&oacute;mico" value="{{ old('destino_economico') }}" maxlength="3">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">N&uacute;mero resoluci&oacute;n:</label>
                                            <input type="text" id="numero_resolucion" name="numero_resolucion" class="form-control" autocomplete="off" placeholder="Ingrese n&uacute;mero resoluci&oacute;n" value="{{ old('numero_resolucion') }}" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">N&uacute;mero &uacute;ltima factura:</label>
                                            <input type="text" id="numero_ultima_factura" name="numero_ultima_factura" class="form-control" autocomplete="off" placeholder="Ingrese n&uacute;mero &uacute;ltima factura" value="{{ old('numero_ultima_factura') }}" maxlength="15">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">C&oacute;digo tarifa:</label>
                                            <select id="id_tarifa_predial" name="id_tarifa_predial" class="form-control selectpicker-noval show-tick" data-live-search="true" data-size="4" title="Seleccione..." data-container="#modal-datos-calculo">
                                                @if(count($tarifas_predial) > 0)
                                                    @foreach($tarifas_predial as $tarifa_predial)
                                                    <option data-subtext="<br />a&ntilde;o: {{ $tarifa_predial->anio }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c&oacute;digo: {{ $tarifa_predial->codigo }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tarifa: {{ $tarifa_predial->tarifa }}" value="{{ $tarifa_predial->id }}" {{ old('id_tarifa_predial') == $tarifa_predial->id ? 'selected' : '' }}>{{ $tarifa_predial->descripcion }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <label for="uso_suelo_check" class="control-label" style="display: block;">¿Uso de suelo?</label>
                                            <input type="checkbox" id="uso_suelo" name="uso_suelo" value="{{ old('uso_suelo') }}">
                                            <span id="span_uso_suelo" class="text-muted" style="padding-left: 10px;">NO</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                 </div>
            </div>
            <div class="modal-footer">
                <button id="save_dc" type="button" class="btn btn-info"> <i class="fa fa-save"></i> Guardar informaci&oacute;n</button>
                <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div> --}}
{{-- <div id="modal-datos-pagos" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-datos-pagos-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-datos-pagos-label">Informaci&oacute;n adicional - <span>Datos pagos</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-predios-datos-pagos">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Banco:</label>
                                            <select id="id_banco" name="id_banco" class="form-control selectpicker-noval show-tick" data-live-search="true" data-size="4" title="Seleccione..." data-container="#modal-datos-pagos">
                                                @if(count($bancos) > 0)
                                                    @foreach($bancos as $banco)
                                                    <option value="{{ $banco->id }}" {{ old('id_banco') == $banco->id ? 'selected' : '' }}>{{ $banco->codigo }} - {{ $banco->nombre }} ({{ $banco->asobancaria }})</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Factura pago:</label>
                                            <input type="text" id="factura_pago" name="factura_pago" class="form-control" autocomplete="off" placeholder="Ingrese factura pago" value="{{ old('factura_pago') }}" maxlength="15">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">&Uacute;ltimo a&ntilde;o pago:</label>
                                            <input type="text" id="ultimo_anio" name="ultimo_anio" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese ultimo a&ntilde;o pago" value="{{ old('ultimo_anio') }}" maxlength="4">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Valor pago:</label>
                                            <input type="text" id="valor_pago_" name="valor_pago_" class="form-control" autocomplete="off" placeholder="Ingrese valor pago" value="{{ old('valor_pago_') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Fecha pago:</label>
                                            <input type="text" id="fecha_pago" name="fecha_pago" class="form-control datepickerforms" autocomplete="off" placeholder="Ingrese fecha pago" value="{{ old('fecha_pago') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <label for="acuerdo_pago_check" class="control-label" style="display: block;">¿Acuerdo de pago?</label>
                                            <input type="checkbox" id="acuerdo_pago" name="acuerdo_pago" value="{{ old('acuerdo_pago') }}">
                                            <span id="span_acuerdo_pago" class="text-muted" style="padding-left: 10px;">NO</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="save_dpa" type="button" class="btn btn-info"> <i class="fa fa-save"></i> Guardar informaci&oacute;n</button>
                <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div> --}}
<div id="modal-datos-acuerdo-pago" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-datos-acuerdo-pago-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-datos-acuerdo-pago-label">Datos acuerdo de pago</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-predios-datos-acuerdos-pago">
                            <input type="hidden" id="anulado_acuerdo" name="anulado_acuerdo" value="0" />
                            <div class="form-body">
                                <div class="row">
                                    <div id="div_acuerdo_numero" class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">N&uacute;mero:</label>
                                            <input type="text" id="numero_acuerdo" name="numero_acuerdo" class="form-control" autocomplete="off" placeholder="N&uacute;mero" value="{{ old('numero_acuerdo') }}" maxlength="10" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Resoluci&oacute;n:</label>
                                            <input type="text" id="numero_resolucion_acuerdo" name="numero_resolucion_acuerdo" class="form-control" autocomplete="off" placeholder="Ingrese resoluci&oacute;n" value="{{ old('numero_resolucion_acuerdo') }}" maxlength="20">
                                            <span class="text-danger">@error('numero_resolucion_acuerdo') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Vigencia inicial:</label>
                                            <select id="anio_inicial_acuerdo" name="anio_inicial_acuerdo" class="form-control" data-size="4" title="Seleccione a&ntilde;o..." data-container="#modal-datos-acuerdo-pago" style="width: 100%">
                                            </select>
                                            {{-- <input type="text" id="anio_inicial_acuerdo" name="anio_inicial_acuerdo" class="form-control onlyNumbers" autocomplete="off" placeholder="A&ntilde;o" value="{{ old('anio_inicial_acuerdo') }}" maxlength="4"> --}}
                                            <span class="text-danger">@error('anio_inicial_acuerdo') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Vigencia final:</label>
                                            <select id="anio_final_acuerdo" name="anio_final_acuerdo" class="form-control" data-size="4" title="Seleccione a&ntilde;o..." data-container="#modal-datos-acuerdo-pago" style="width: 100%">
                                            </select>
                                            {{-- <input type="text" id="anio_final_acuerdo" name="anio_final_acuerdo" class="form-control onlyNumbers" autocomplete="off" placeholder="A&ntilde;o" value="{{ old('anio_final_acuerdo') }}" maxlength="4"> --}}
                                            <span class="text-danger">@error('anio_final_acuerdo') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                    <div id="div_fecha_acuerdo" class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Fecha acuerdo:</label>
                                            <input type="text" id="fecha_acuerdo" name="fecha_acuerdo" class="form-control datepicker" autocomplete="off" placeholder="Fecha acuerdo" value="{{ old('fecha_acuerdo') }}" style="width: 100%;">
                                            <span class="text-danger">@error('fecha_acuerdo') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Cuotas:</label>
                                            <input type="text" id="cuotas_acuerdo" name="cuotas_acuerdo" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese cuotas" value="{{ old('cuotas_acuerdo') }}" maxlength="2">
                                            <span class="text-danger">@error('cuotas_acuerdo') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">D&iacute;a pago:</label>
                                            <input type="text" id="dia_pago_acuerdo" name="dia_pago_acuerdo" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese d&iacute;a" value="{{ old('dia_pago_acuerdo') }}" maxlength="2">
                                            <span class="text-danger">@error('dia_pago_acuerdo') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Abono inicial:</label>
                                            <input type="text" id="abono_inicial_acuerdo" name="abono_inicial_acuerdo" class="form-control" autocomplete="off" placeholder="Ingrese valor" value="{{ old('abono_inicial_acuerdo') }}">
                                            <span class="text-danger">@error('abono_inicial_acuerdo') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Total acuerdo</label>
                                            <br />
                                            <p id="total_acuerdo" class="text-info" style="font-size: 160%; font-weight: 500"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" id="div_ver_detalle_ap" style="display: none;">
                                        <div class="form-group">
                                            <br />
                                            <button id="ver_detalle_ap" type="button" class="btn btn-info"> <i class="fa fa-eye"></i> Ver detalle</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="responsable_propietario_acuerdo_check" class="control-label" style="display: block;">¿Responsable propietario?</label>
                                            <input type="checkbox" id="responsable_propietario_acuerdo" name="responsable_propietario_acuerdo" value="{{ old('responsable_propietario_acuerdo') }}" checked>
                                            <span id="span_responsable_propietario_acuerdo" class="text-muted" style="padding-left: 10px;">SI</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Identificaci&oacute;n:</label>
                                            <input type="text" id="identificacion_acuerdo" name="identificacion_acuerdo" class="form-control" autocomplete="off" placeholder="Ingrese identificaci&oacute;n" value="{{ old('identificacion_acuerdo') }}" maxlength="30">
                                            <span class="text-danger">@error('identificacion_acuerdo') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Nombre:</label>
                                            <input type="text" id="nombre_acuerdo" name="nombre_acuerdo" class="form-control" autocomplete="off" placeholder="Ingrese nombre" value="{{ old('nombre_acuerdo') }}" maxlength="128">
                                            <span class="text-danger">@error('nombre_acuerdo') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Tel&eacute;fono:</label>
                                            <input type="text" id="telefono_acuerdo" name="telefono_acuerdo" class="form-control" autocomplete="off" placeholder="Ingrese tel&eacute;fono" value="{{ old('telefono_acuerdo') }}" maxlength="10">
                                            <span class="text-danger">@error('telefono_acuerdo') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Direcci&oacute;n:</label>
                                            <input type="text" id="direccion_acuerdo" name="direccion_acuerdo" class="form-control" autocomplete="off" placeholder="Ingrese direcci&oacute;n" value="{{ old('direccion_acuerdo') }}" maxlength="128">
                                            <span class="text-danger">@error('direccion_acuerdo') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                 </div>
            </div>
            <div class="modal-footer">
                <button id="save_dap" type="button" class="btn btn-info"> <i class="fa fa-save"></i> Guardar informaci&oacute;n</button>
                <button id="anular_dap" type="button" class="btn btn-danger"> <i class="fa fa-save"></i> Anular acuerdo de pago</button>
                <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="modal-datos-abonos" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-datos-abonos-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-datos-abonos-label">Informaci&oacute;n adicional - <span>Datos abonos</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-predios-datos-abonos">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <label class="control-label">A&ntilde;o abono:</label>
                                            <input type="text" id="anio_abono" name="anio_abono" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese a&ntilde;o abono" value="{{ old('anio_abono') }}" maxlength="4">
                                            {{-- <span class="text-danger">@error('anio_abono') {{ $message }} @enderror</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <label class="control-label">Factura abono:</label>
                                            <input type="text" id="factura_abono" name="factura_abono" class="form-control" autocomplete="off" placeholder="Ingrese factura abono" value="{{ old('factura_abono') }}" maxlength="15">
                                            {{-- <span class="text-danger">@error('factura_abono') {{ $message }} @enderror</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 5px;">
                                            <label class="control-label">Valor abono:</label>
                                            <input type="text" id="valor_abono" name="valor_abono" class="form-control" autocomplete="off" placeholder="Ingrese valor abono" value="{{ old('valor_abono') }}">
                                            {{-- <span class="text-danger">@error('valor_abono') {{ $message }} @enderror</span> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6" style="font-size: 80%;">
                                        <span id="text_page_abonos" class="text-muted info_abonos">Edici&oacute;n<br />P&aacute;gina: 1</span><br />
                                        <span id="text_row_abonos" class="text-muted info_abonos">Fila: 1</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="divAbonosTable" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none;">
                        <h2>Lista de abonos</h2>
                        <table id="abonosTable" class="table table-hover table-condensed table-striped table-bordered" style="margin-bottom: 10px;"></table>
                    </div>
                 </div>
            </div>
            <div class="modal-footer">
                <button id="new_da" type="button" class="btn btn-success pull-left" style="display: none;"> <i class="fa fa-cogs"></i> Nuevo</button>
                <button id="cancel_da" type="button" class="btn btn-default pull-left" style="display: none;"> <i class="fa fa-cogs"></i> Cancelar</button>
                <button id="save_da" type="button" class="btn btn-info"> <i class="fa fa-save"></i> Guardar informaci&oacute;n</button>
                <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="modal-datos-procesos-historicos" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-datos-procesos-historicos-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-datos-procesos-historicos-label">Informaci&oacute;n adicional - <span>Procesos e Hist&oacutericos</h4>
            </div>
            <div class="modal-body">
                <table id="avaluosTable" class="table table-hover table-condensed table-striped table-bordered" style="margin-bottom: 10px;"></table>
                {{-- <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-predios-datos-procesos-historicos">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> --}}
            </div>
            <div class="modal-footer">
                <button id="print_avaluos" url="/generate_avaluos_predio_pdf/" type="button" class="btn btn-youtube pull-left btn_pdf"> <i class="fa fa-file-pdf-o"></i> Descargar PDF</button>
                <button id="excel_avaluos" tipo="avaluos" type="button" class="btn btn-success pull-left btn_excel excel_avaluos_estado"> <i class="fa fa-file-excel-o"></i> Generar EXCEL</button>
                <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="modal-datos-estado-cuenta" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-datos-estado-cuenta-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-datos-estado-cuenta-label">Informaci&oacute;n adicional - <span>Estado de Cuenta</h4>
            </div>
            <div class="modal-body">
                <table id="estadoCuentaTable" class="table table-hover table-condensed table-striped table-bordered" style="margin-bottom: 10px;"></table>
            </div>
            <div class="modal-footer">
                <button id="print_estado_cuenta" url="/generate_estado_cuenta_predio_pdf/" type="button" class="btn btn-youtube pull-left btn_pdf" style="display: none;"> <i class="fa fa-file-pdf-o"></i> Descargar PDF</button>
                <button id="excel_estado_cuenta" tipo="estado_cuenta" type="button" class="btn btn-success pull-left btn_excel excel_avaluos_estado" style="display: none;"> <i class="fa fa-file-excel-o"></i> Generar EXCEL</button>
                <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="modal-batch" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-batch-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-batch-label">
                    Procesar c&aacute;lculo batch
                    <span class="pull-right text-danger">Vigencia: <b id="span_anio_batch"></b></span>
                </h4>
            </div>
            <div class="modal-body">
                {{-- <table id="batchPrediosTable" class="table table-hover table-condensed table-striped table-bordered" style="margin-bottom: 10px;"></table> --}}
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-predios-batch">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        {{-- <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12"> --}}
                                            <div class="form-group">
                                                <label class="control-label">Predio inicial:</label>
                                                <label class="text-muted pull-right" style="font-weight: normal;">Total predios sin c&aacute;lculo: <h6 id="span_total_predios" class="text-muted" style="display: contents; font-weight: bold;"></h6></label>
                                                <select id="id_predio_inicial" name="id_predio_inicial" class="form-control" data-live-search="true" data-size="4" title="Sin informaci&oacute;n...">
                                                </select>
                                            </div>
                                        {{-- </div> --}}
                                    </div>
                                    <div id="div_id_predio_final" class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="display: none;">
                                        {{-- <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12"> --}}
                                            <div class="form-group">
                                                <label class="control-label">Predio final:</label>
                                                <label class="text-muted pull-right" style="font-weight: normal;">Bloque disponible: <h6 id="span_disponibles_predios" class="text-muted" style="display: contents; font-weight: bold;"></h6></label>
                                                <select id="id_predio_final" name="id_predio_final" class="form-control" data-live-search="true" data-size="4" title="Sin informaci&oacute;n...">
                                                </select>
                                            </div>
                                        {{-- </div> --}}
                                    </div>
                                </div>
                                <div id="div_resumen_batch" class="row resumen_batch" style="display: none;">
                                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                        <h3 class="box-title">Resumen de solicitud ejecuci&oacute;n c&aacute;lculo predial batch</h3>
                                        <ul class="list-icons">
                                            <li id="li_predio_inicial" class=" resumen_batch"><i class="fa fa-check text-success"></i> <b>Predio inicial:</b> <span id="span_predio_inicial"></span></li>
                                            <li id="li_predio_final" class=" resumen_batch"><i class="fa fa-check text-success"></i> <b>Predio final:</b> <span id="span_predio_final"></span></li>
                                            <li id="li_cantidad" class=" resumen_batch"><i class="fa fa-check text-success"></i> <b>Cantidad de predios para operar:</b> <span id="span_cantidad"></span></li>
                                        </ul>
                                    </div>
                                    <div id="div_btn_ejecutar_calculo_batch" class="col-lg-5 col-md-5 col-sm-12 col-xs-12 resumen_batch">
                                        <button id="btn_ejecutar_calculo_batch" type="button" class="btn btn-success"> <i class="fa fa-cubes"></i> Ejecutar proceso</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{-- <button id="btn_excel_batch_predios" url="/generate_excel_batch_predios/" type="button" class="btn btn-success pull-left"> <i class="fa fa-file-excel-o"></i> Generar excel</button> --}}
                <button type="button" class="btn btn-default waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('resolucion')
    @if($opcion->resolucion_elimina == 1 || $opcion->resolucion_edita == 1)
        @include('resoluciones.modal')
    @endif
@endsection
