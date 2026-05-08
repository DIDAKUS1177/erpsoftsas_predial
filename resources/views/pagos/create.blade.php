@extends('theme.default')

@section('content')

@push('scripts')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\PagosCreateFormRequest', '#create-form'); !!}
    {!! JsValidator::formRequest('App\Http\Requests\PagosUpdateFormRequest', '#update-form'); !!}
    <script src="{!! asset('theme/js/accounting.min.js') !!}"></script>
    <script src="{!! asset('theme/js/autonumeric.min.js') !!}"></script>
    <script src="{!! asset('theme/js/jquery.form.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/jquery.serializeJSON/jquery.serializejson.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/jquery-validation-1.19.5/jquery.validate.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/blockUI/jquery.blockUI.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/bootstrap-filestyle/bootstrap-filestyle.min.js') !!}"></script>
    {{-- <div class="modal-header"> --}}
    <script src="{!! asset('theme/js/customjs/controlsite.js') !!}?{{ $current_time }}"></script>
   <script src="{!! asset('theme/js/customjs/pagos.js') !!}?{{ $current_time }}"></script>
    {{-- <script src="{!! asset('theme/js/customjs/pagos_forms.js') !!}"></script> --}}
@endpush

<style>
    .progress { width:100%; }
    .bar { background-color: #10d831; width:0%; height:20px; }
    .porciento { position:absolute; display:none; color: #040608;}
</style>

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
                                <li id="li-section-bar-1" class="tab-current"><a href="#section-bar-1" class="sticon ti-pulse"><span>Nuevo pago</span></a></li>
                                <li id="li-section-bar-2" class="">
                                    <a href="#section-bar-2" class="sticon icon-list"><span>Listado de pagos</span></a>
                                </li>
                                <!-- <li class=""><a href="#section-bar-3" class="sticon ti-stats-up"><span>Analytics</span></a></li>
                                <li class=""><a href="#section-bar-4" class="sticon ti-upload"><span>Upload</span></a></li>
                                <li class=""><a href="#section-bar-5" class="sticon ti-settings"><span>Settings</span></a></li> -->
                            </ul>
                        </nav>
                        <div class="content-wrap">
                            <section id="section-bar-1" class="content-current">
                                <div class="panel panel-inverse">
                                    <div class="panel-heading">
                                        <i class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Informaci&oacute;n del pago
                                        <i id="search-pago" class="fa fa-search pull-right icons-tasks" style="cursor: pointer; padding-left: 15px;"></i>
                                        <i id="upload-asobancaria" class="fa fa-upload pull-right icons-tasks" style="cursor: pointer;"></i>
                                    </div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <form action="{{ route('pagos.create_pagos') }}" method="post" id="create-form">
                                                <input type="hidden" id="id_predio" name="id_predio" value="">
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
                                                    <!-- <h3 class="box-title">Informaci&oacute;n de la pago</h3> -->
                                                    <!-- <hr> -->
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Banco factura:</label>
                                                                <select id="id_banco_factura" name="id_banco_factura" class="form-control selectpicker show-tick" data-live-search="true" data-container="body" data-size="4" title="Sin informaci&oacute;n...">
                                                                    @if(count($bancos) > 0)
                                                                        @foreach($bancos as $banco)
                                                                        <option value="{{ $banco->id }}" {{ old('id_banco_factura') == $banco->id ? 'selected' : '' }}>{{ $banco->codigo }} - {{ $banco->nombre }} ({{ $banco->asobancaria }})</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <span class="text-danger">@error('id_banco_factura') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Fecha pago:</label>
                                                                <input type="text" id="fecha_pago" name="fecha_pago" class="form-control datepicker" autocomplete="off" placeholder="Ingrese fecha pago" value="{{ old('fecha_pago') }}" readonly="readonly" style="background-color: white; width: 100%;">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">N&uacute;mero factura:</label>
                                                                <input type="text" id="numero_recibo" name="numero_recibo" class="form-control onlyNumbers" autocomplete="off" placeholder="N&uacute;mero de factura" value="{{ old('numero_recibo') }}" maxlength="9" disabled="disabled">
                                                                <span class="text-danger">@error('numero_recibo') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-6 col-sm-7 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Banco archivo:</label>
                                                                <select id="id_banco_archivo" name="id_banco_archivo" class="form-control selectpicker-noval show-tick" data-live-search="true" data-size="4" title="Sin informaci&oacute;n...">
                                                                    @if(count($bancos) > 0)
                                                                        @foreach($bancos as $banco)
                                                                        <option value="{{ $banco->id }}" {{ old('id_banco_archivo') == $banco->id ? 'selected' : '' }}>{{ $banco->codigo }} - {{ $banco->nombre }} ({{ $banco->asobancaria }})</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Paquete archivo:</label>
                                                                <input type="text" id="paquete_archivo" name="paquete_archivo" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese n&uacute;mero de paquete" value="{{ old('paquete_archivo') }}" maxlength="2">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo predio:</label>
                                                                {{-- <select id="id_predio" name="id_predio" class="form-control selectpicker show-tick" data-live-search="true" data-size="4" title="Sin informaci&oacute;n...">
                                                                    @if(count($predios) > 0)
                                                                        @foreach($predios as $predio)
                                                                        <option value="{{ $predio->id }}" {{ old('id_predio') == $predio->id ? 'selected' : '' }}>{{ $predio->codigo_predio }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select> --}}
                                                                {{-- <select id="id_predio" class="form-control select2" name="id_predio">
                                                                </select>
                                                                <span class="text-danger">@error('id_predio') {{ $message }} @enderror</span> --}}
                                                                <input type="text" id="codigo_predio" name="codigo_predio" class="form-control onlyNumbers" autocomplete="off" placeholder="C&oacute;digo predio" value="{{ old('codigo_predio') }}" maxlength="128" readonly="readonly">
                                                                <span class="text-danger">@error('codigo_predio') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Valor facturado:</label>
                                                                <input type="text" id="valor_facturado" name="valor_facturado" class="form-control" autocomplete="off" placeholder="Ingrese valor" value="{{ old('valor_facturado') }}">
                                                                <span class="text-danger">@error('valor_facturado') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Fecha factura:</label>
                                                                <input type="text" id="fecha_factura" name="fecha_factura" class="form-control" autocomplete="off" placeholder="Ingrese fecha factura" value="{{ old('fecha_factura') }}" readonly="readonly">
                                                                <span class="text-danger">@error('fecha_factura') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                                            <div class="form-group" style="margin-bottom: 5px;">
                                                                <label class="control-label">A&ntilde;o factura:</label>
                                                                <input type="text" id="anio_pago" name="anio_pago" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese a&ntilde;o" value="{{ old('anio_pago') }}" maxlength="4" readonly="readonly">
                                                                <span class="text-danger">@error('anio_pago') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo de barras:</label>
                                                                <input type="text" id="codigo_barras" name="codigo_barras" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese c&oacute;digo de barras" value="{{ old('codigo_barras') }}" maxlength="128">
                                                                <span class="text-danger">@error('codigo_barras') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-actions m-t-20">
                                                    <button id="btn_save_info" type="submit" class="btn btn-info"> <i class="fa fa-save"></i> Guardar informaci&oacute;n</button>
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
                                            {{-- @if(isset($pagos)) --}}
                                                {{-- <div class="result">
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
                                                </div> --}}
                                                <h2>
                                                    <span>Lista de pagos</span>
                                                    <div class="pull-right" style="font-size: 55%;">
                                                        <label>
                                                            <mark>Buscar una factura:</mark>
                                                            <input id="chk_search_factura" type="checkbox" value="0" class="js-switch" data-color="#99d683" data-size="small" />
                                                        </label>
                                                    </div>
                                                    <hr />
                                                </h2>
                                                <form id="pagos-filtro-form">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-5 col-xs-12 search_general">
                                                            <div class="form-group">
                                                                <label class="control-label">Fecha inicial:</label>
                                                                <input type="text" id="fecha_pago_inicial" name="fecha_pago_inicial" class="form-control datepicker" autocomplete="off" placeholder="Seleccione  fecha pago inicial" value="{{ old('fecha_pago_inicial') }}" readonly="readonly" style="background-color: white;">
                                                                {{-- <span class="text-danger">@error('fecha_pago_inicial') {{ $message }} @enderror</span> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-5 col-xs-12 search_general">
                                                            <div class="form-group">
                                                                <label class="control-label">Fecha final:</label>
                                                                <input type="text" id="fecha_pago_final" name="fecha_pago_final" class="form-control datepicker" autocomplete="off" placeholder="Seleccione  fecha pago final" value="{{ old('fecha_pago_final') }}" readonly="readonly" style="background-color: white;">
                                                                {{-- <span class="text-danger">@error('fecha_pago_final') {{ $message }} @enderror</span> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 search_general">
                                                            <div class="form-group" style="margin-bottom: 0px;">
                                                                <label class="control-label">Seleccione banco inicial:</label>
                                                                <select id="id_banco_inicial" name="id_banco_inicial" class="form-control selectpicker-noval show-tick withadon" data-live-search="true" data-container="body" data-size="4" title="Sin informaci&oacute;n...">
                                                                    @if(count($bancos) > 0)
                                                                        @foreach($bancos as $banco)
                                                                        <option value="{{ $banco->id }}" {{ old('id_banco_inicial') == $banco->id ? 'selected' : '' }}>({{ $banco->id }}) - {{ $banco->nombre }} ({{ $banco->asobancaria }})</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <span class="text-danger">@error('id_banco_inicial') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 search_general">
                                                            <div class="form-group" style="margin-bottom: 0px;">
                                                                <label class="control-label">Seleccione banco final:</label>
                                                                <select id="id_banco_final" name="id_banco_final" class="form-control selectpicker-noval show-tick withadon" data-live-search="true" data-container="body" data-size="4" title="Sin informaci&oacute;n..." disabled="disabled">
                                                                    @if(count($bancos) > 0)
                                                                        @foreach($bancos as $banco)
                                                                        <option value="{{ $banco->id }}" {{ old('id_banco_final') == $banco->id ? 'selected' : '' }}>({{ $banco->id }}) - {{ $banco->nombre }} ({{ $banco->asobancaria }})</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <span class="text-danger">@error('id_banco_final') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search_general">
                                                            <div class="form-group">
                                                                <label class="control-label" style="display: block;"><br /></label>
                                                                <button type="button"  id="btn_buscar_pagos" class="btn btn-info"><i class="fa fa-save"></i> Filtrar pagos</button>
                                                                <button type="button"  id="btn_descargar_pagos" class="btn btn-success pull-right" style="display: none;"><i class="fa fa-file-excel-o"></i> Descargar EXCEL</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 search_factura" style="display: none;">
                                                            <div class="form-group">
                                                                <label class="control-label">N&uacute;mero factura:</label>
                                                                <input type="text" id="numero_factura_search" name="numero_factura_search" class="form-control onlyNumbers" autocomplete="off" placeholder="N&uacute;mero de factura" value="{{ old('numero_factura_search') }}" maxlength="9">
                                                                <span class="text-danger">@error('numero_factura_search') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-4 search_factura" style="display: none;">
                                                            <div class="form-group">
                                                                <label class="control-label" style="display: block;"><br /></label>
                                                                <button type="button" id="btn_buscar_factura" class="btn btn-default" disabled="disabled"><i class="fa fa-search"></i> Buscar factura</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12 search_factura" style="display: none;">
                                                            <div class="form-group">
                                                                <code><b>Atenci&oacute;n!</b></code><br /><code>La edici&oacute;n de informaci&oacute;n solo es permitida para pagos que hayan sido cargados manualmente.</code>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <table id="pagosTable" class="table table-hover table-striped table-bordered">
                                                </table>
                                                {{-- <div class="pagination-blobk">
                                                    {{ $pagos->links('layouts.paginationlinks') }}
                                                </div> --}}
                                                {{-- <form id="form_delete" action="{{ route('pagos.delete_pagos') }}" method="post" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" id="input_delete" name="input_delete">
                                                </form> --}}
                                            {{-- @endif --}}
                                        </div>
                                    </div>
                                </div>
                                <div id="div_logs" class="row" style="display: none;">
                                    <hr >
                                    <h3 class="box-title m-b-0">INFORMACI&Oacute;N DE LOG GENERADA...</h3>
                                    <table id="logsTable" class="table table-hover table-striped table-bordered">
                                    </table>
                                </div>
                                {{-- <div id="div_edit_form" class="panel panel-info" style="display: none; margin-bottom: 0px">
                                    <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Actualizar informaci&oacute;n del pago</div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <form action="{{ route('pagos.update_pagos') }}" method="post" id="update-form">
                                                @csrf
                                                <div class="form-body">
                                                    <input type="hidden" id="id_edit" name="id_edit" value="{{ old('id_edit') }}">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-5 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Fecha pago:</label>
                                                                <input type="text" id="fecha_pago_edit" name="fecha_pago_edit" class="form-control" autocomplete="off" placeholder="Ingrese fecha pago" value="{{ old('fecha_pago_edit') }}" readonly="true">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Banco archivo:</label>
                                                                <select id="id_banco_archivo_edit" name="id_banco_archivo_edit" class="form-control selectpicker-noval show-tick" data-live-search="true" data-size="4" title="Sin informaci&oacute;n..." disabled="true">
                                                                    @if(count($bancos) > 0)
                                                                        @foreach($bancos as $banco)
                                                                        <option value="{{ $banco->id }}" {{ old('id_banco_archivo_edit') == $banco->id ? 'selected' : '' }}>{{ $banco->codigo }} - {{ $banco->nombre }} ({{ $banco->asobancaria }})</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Paquete archivo:</label>
                                                                <input type="text" id="paquete_archivo_edit" name="paquete_archivo_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese n&uacute;mero de paquete" value="{{ old('paquete_archivo_edit') }}" readonly="true">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo de barras:</label>
                                                                <input type="text" id="codigo_barras_edit" name="codigo_barras_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese c&oacute;digo de barras" value="{{ old('codigo_barras_edit') }}" maxlength="128">
                                                                <span class="text-danger">@error('codigo_barras_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">N&uacute;mero factura:</label>
                                                                <input type="text" id="numero_recibo_edit" name="numero_recibo_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="N&uacute;mero de recibo" value="{{ old('numero_recibo_edit') }}" maxlength="9">
                                                                <span class="text-danger">@error('numero_recibo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo predio:</label>
                                                                <select id="id_predio_edit" name="id_predio_edit" class="form-control selectpicker show-tick" data-live-search="true" data-size="4" title="Sin informaci&oacute;n...">
                                                                    @if(count($predios) > 0)
                                                                        @foreach($predios as $predio)
                                                                        <option value="{{ $predio->id }}" {{ old('id_predio_edit') == $predio->id ? 'selected' : '' }}>{{ $predio->codigo_predio }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <span class="text-danger">@error('id_predio_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Valor facturado:</label>
                                                                <input type="text" id="valor_facturado_edit" name="valor_facturado_edit" class="form-control" autocomplete="off" placeholder="Ingrese valor" value="{{ old('valor_facturado_edit') }}">
                                                                <span class="text-danger">@error('valor_facturado_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Fecha factura:</label>
                                                                <input type="text" id="fecha_factura_edit" name="fecha_factura_edit" class="form-control datepicker" autocomplete="off" placeholder="Ingrese fecha factura" value="{{ old('fecha_factura_edit') }}">
                                                                <span class="text-danger">@error('fecha_factura_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Banco factura:</label>
                                                                <select id="id_banco_factura_edit" name="id_banco_factura_edit" class="form-control selectpicker show-tick" data-live-search="true" data-size="4" title="Sin informaci&oacute;n...">
                                                                    @if(count($bancos) > 0)
                                                                        @foreach($bancos as $banco)
                                                                        <option value="{{ $banco->id }}" {{ old('id_banco_factura_edit') == $banco->id ? 'selected' : '' }}>{{ $banco->codigo }} - {{ $banco->nombre }} ({{ $banco->asobancaria }})</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <span class="text-danger">@error('id_banco_factura_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                                            <div class="form-group" style="margin-bottom: 5px;">
                                                                <label class="control-label">A&ntilde;o factura:</label>
                                                                <input type="text" id="anio_pago_edit" name="anio_pago_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese a&ntilde;o" value="{{ old('anio_pago_edit') }}" maxlength="4">
                                                                <span class="text-danger">@error('anio_pago_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-actions m-t-20">
                                                    <button type="submit" class="btn btn-info"> <i class="fa fa-save"></i> Actualizar informaci&oacute;n</button>
                                                    <button id="btn_cancel_edit" type="button" class="btn btn-default"> <i class="fa fa-thumbs-down"></i> Cancelar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> --}}
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
@endsection

@section('modales')
<div id="modal-carga-archivo-asobancaria" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-carga-archivo-asobancaria-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-carga-archivo-asobancaria-label">Carga de archivo asobancaria.</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        {{-- <form id="form-carga-archivo-asobancaria"> --}}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        {{-- <div class="form-group">
                                            <label class="control-label">Seleccione archivo:</label>
                                            <input type="file" id="asobancaria" name="asobancaria" class="form-control" autocomplete="off" placeholder="Seleccione archivo">
                                        </div> --}}
                                        <input type="hidden" id="filename" value="">
                                        <input type="hidden" id="fileid" value="">
                                        <form id="load-form" action="{{route('upload-file-asobancaria')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Archivo pagos asobancaria</label><span class="text-muted" style="margin-left: 15px;">archivo .txt</span>
                                                            <input type="file" accept=".txt" id="file" name="file" class="form-control filestyle" data-placeholder="" value="{{ old('file') }}" data-buttonName="btn-inverse" data-buttonBefore="true" data-buttonText="Buscar archivo">
                                                            <span class="text-danger">@error('file') {{ $message }} @enderror</span>
                                                        </div>
                                                        <div class="progress">
                                                            <div class="bar one"></div >
                                                            <div class="porciento one">0%</div >
                                                        </div>
                                                        <span id="error_fileupload" class="text-danger" style="display: none;">Max file size 10 Mb</span>
                                                        <span id="current_filename" class="text-inverse" style="display: block; padding-top: 15px;"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="form-actions m-t-20">
                                                <button id="btn_upload" type="submit" class="btn btn-info"> <i class="fa fa-upload"></i> Cargar archivo</button>
                                                <span id="current_filename" class="text-inverse" style="display: block; padding-top: 15px;"></span>
                                            </div> --}}
                                        </form>
                                    </div>
                                </div>
                            </div>
                        {{-- </form> --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn_cargar_archivo_asobancaria" type="button" class="btn btn-info"> <i class="fa fa-upload"></i> Cargar archivo</button>
                <button type="button" class="btn btn-danger btnasobancaria" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="modal-search-recibo" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-search-recibo-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-search-recibo-label">B&uacute;squeda factura de pago</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">N&uacute;mero factura</label>
                            <input type="text" id="numero_recibo_search" name="numero_recibo_search" class="form-control onlyNumbers" maxlength="9" value="{{ old('numero_recibo_search') }}">
                            <span class="text-danger">@error('file') {{ $message }} @enderror</span>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-3 col-sm-6" style="display: none;">
                        <br />
                        <button id="btn_buscar_recibo" type="button" class="btn btn-default" style="margin-top: 5px; padding: 8px 12px;" disabled="disabled"> <i class="fa fa-search"></i></button>
                    </div>
                    <div id="div_descargar_factura" class="col-lg-4 col-md-4 col-sm-3 col-sm-12" style="display: none;">
                        <br />
                        <button id="print_factura" url="/generate_factura_pdf/" type="button" class="btn btn-youtube btn_pdf" style="margin-top: 5px; padding: 8px 12px;"> <i class="fa fa-file-pdf-o"></i> Generar PDF</button>
                    </div>
                </div>
                <div class="row info_factura" style="display: none;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {{-- <div class="white-box"> --}}
                            {{-- <h3 class="box-title m-b-0">B&uacute;squeda de informaci&oacute;n</h3> --}}
                            {{-- <p class="text-muted m-b-30 font-13"> Factura de pago </p> --}}
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <form id="form-info-factura">
                                        <input type="hidden" id="info_id_predio">
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label for="info_numero">N&uacute;mero</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="info_numero" placeholder="N&uacute;mero" readonly="readonly">
                                                        <div class="input-group-addon"><i class="ti-list-ol"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="info_anio">A&ntilde;o</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="info_anio" placeholder="A&ntilde;o" readonly="readonly">
                                                        <div class="input-group-addon"><i class="ti-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="info_avaluo">Aval&uacute;o</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="info_avaluo" placeholder="Aval&uacute;o" readonly="readonly">
                                                        <div class="input-group-addon"><i class="ti-money"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label for="info_fecha_emision">Fecha emisi&oacute;n</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="info_fecha_emision" placeholder="Fecha emisi&oacute;n" readonly="readonly">
                                                        <div class="input-group-addon"><i class="ti-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="info_fecha_vencimiento">Fecha vencimiento</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="info_fecha_vencimiento" placeholder="Fecha vencimiento" readonly="readonly">
                                                        <div class="input-group-addon"><i class="ti-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="info_anulado">Anulado?</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="info_anulado" placeholder="Anulado?" readonly="readonly">
                                                        <div class="input-group-addon"><i class="ti-close"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label for="info_pagado">Pagado?</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="info_pagado" placeholder="Pagado?" readonly="readonly">
                                                        <div class="input-group-addon"><i class="ti-check"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label for="info_valor_factura">Valor factura</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="info_valor_factura" placeholder="Valor factura" readonly="readonly">
                                                        <div class="input-group-addon"><i class="ti-money"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="info_fecha_pago">Fecha pago</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="info_fecha_pago" placeholder="Fecha pago" readonly="readonly" style="background-color: white;">
                                                        <div class="input-group-addon"><i class="ti-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="info_banco">Banco</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="info_banco" placeholder="Banco" readonly="readonly">
                                                        <div class="input-group-addon"><i class="ti-home"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="modal-log" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-log-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modal-log-label">Log carga archivo asobancaria</h4>
            </div>
            <div id="div_show_log" class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="modal-editar-pago" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-editar-pago-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm" style="width: 24%;">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-editar-pago-label">Editar pago</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-predios-editar-pago">
                            <input type="hidden" id="id_pago_edit" name="id_pago_edit" value="">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">N&uacute;mero factura:</label>
                                            <input type="text" id="numero_recibo_edit" name="numero_recibo_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="N&uacute;mero de factura" value="{{ old('numero_recibo_edit') }}" maxlength="9" disabled="disabled">
                                            <span class="text-danger">@error('numero_recibo_edit') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Banco:</label>
                                            <select id="id_banco_factura_edit" name="id_banco_factura_edit" class="form-control selectpicker show-tick" data-live-search="true" data-size="4" title="Sin informaci&oacute;n...">
                                                @if(count($bancos) > 0)
                                                    @foreach($bancos as $banco)
                                                    <option value="{{ $banco->id }}" {{ old('id_banco_factura_edit') == $banco->id ? 'selected' : '' }}>{{ $banco->codigo }} - {{ $banco->nombre }} ({{ $banco->asobancaria }})</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="text-danger">@error('id_banco_factura_edit') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Fecha pago:</label>
                                            <input type="text" id="fecha_pago_edit" name="fecha_pago_edit" class="form-control datepicker" autocomplete="off" placeholder="Ingrese fecha pago" value="{{ old('fecha_pago_edit') }}" readonly="readonly" style="background-color: white; width: 100%;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button id="btn_guardar_editar_pago" type="button" class="btn btn-info"> <i class="fa fa-save"></i> Guardar</button>
                <button type="button" class="btn btn-danger" style="margin-top: 5px;" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
@endsection
