@extends('theme.default')

@section('content')

@push('scripts')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\AcuerdosCreateFormRequest', '#create-form'); !!}
    {!! JsValidator::formRequest('App\Http\Requests\AcuerdosUpdateFormRequest', '#update-form'); !!}
    <script src="{!! asset('theme/js/accounting.min.js') !!}"></script>
    <script src="{!! asset('theme/js/autonumeric.min.js') !!}"></script>
    <script src="{!! asset('theme/js/jquery.form.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/jquery.serializeJSON/jquery.serializejson.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/blockUI/jquery.blockUI.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/bootstrap-filestyle/bootstrap-filestyle.min.js') !!}"></script>
    <script src="{!! asset('theme/js/customjs/controlsite.js') !!}?{{ $current_time }}"></script>
    <script src="{!! asset('theme/js/customjs/load_file.js') !!}?{{ $current_time }}"></script>
    <script src="{!! asset('theme/js/customjs/acuerdos.js') !!}?{{ $current_time }}"></script>
@endpush
@if(Session::get('tab_current'))
<input type="hidden" id="tab" value="{{ Session::get('tab_current') }}">
@elseif($tab_current)
<input type="hidden" id="tab" value="{{ $tab_current }}">
@endif
<input type="hidden" id="opcion" value='@json($opcion)'>
<input type="hidden" id="interfaz" value='acuerdos'>
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
                                <li id="li-section-bar-1" class="tab-current"><a href="#section-bar-1" class="sticon ti-check-box"><span>Nuevo acuerdo de pago</span></a></li>
                                <li id="li-section-bar-2" class=""><a href="#section-bar-2" class="sticon icon-list"><span>Listado de acuerdos de pago</span></a></li>
                                <!-- <li class=""><a href="#section-bar-3" class="sticon ti-stats-up"><span>Analytics</span></a></li>
                                <li class=""><a href="#section-bar-4" class="sticon ti-upload"><span>Upload</span></a></li>
                                <li class=""><a href="#section-bar-5" class="sticon ti-settings"><span>Settings</span></a></li> -->
                            </ul>
                        </nav>
                        <div class="content-wrap" style="min-height: 420px;">
                            <section id="section-bar-1" class="content-current">
                                <div class="panel panel-inverse">
                                    <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Informaci&oacute;n del acuerdo</div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            @if($opcion->resolucion_crea == 1)
                                            {{--
                                                Aqui se establecen todos los campos que se desee validar antes de enviar el formulario.
                                                Esto aplica solo para los formularios que necesitan una resolucion para edicion.
                                            --}}
                                            {{-- Campos --}}
                                            {{-- <input class="resolucion_validate_field_level-create-form" type="hidden" field="codigo_predio_edit" value="" /> --}}

                                            {{--
                                                Aqui se establece el id del formulario que se desea validar.
                                                Esto aplica solo para los formularios que necesitan una resolucion para edicion.
                                                Si se usa validacion de formulario no se deben usar campos aislados.
                                                En caso de que se use validacion de campos y formulario, se ignora los campos aislados
                                                y se hace una validacion completa del formulario.
                                                Cada campo dentro del formulario necesita llevar la clase res-validate.
                                            --}}
                                            {{-- Formulario --}}
                                            <input class="resolucion_validate_form_level-create-form" type="hidden" value="create-form" />
                                            @endif
                                            <div class="form-body">
                                                <!-- <h3 class="box-title">Informaci&oacute;n del acuerdo de pago</h3> -->
                                                <!-- <hr> -->
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
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">Buscar predio:</label>
                                                                    <select id="id_predio" class="form-control select2 json basico res-validate" name="id_predio" data-placeholder="C&oacute;digo, propietario o direcci&oacute;n..." style="width: 100%">
                                                                    </select>
                                                                    <span class="text-danger">@error('id_predio') {{ $message }} @enderror</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div id="select_factura" class="col-lg-2 col-md-2 col-sm-3 col-xs-12" style="display: none;">
                                                        <div class="form-group">
                                                            <label class="control-label">Factura:</label>
                                                            <select id="numero_factura" name="numero_factura" class="form-control selectpicker show-tick" data-live-search="true" data-size="4" title="Factura...">
                                                            </select>
                                                            <span class="text-danger">@error('numero_factura') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div id="select_anios" class="col-lg-1 col-md-1 col-sm-3 col-xs-12" style="display: none;">
                                                        <div class="form-group">
                                                            <label class="control-label">A&ntilde;o:</label>
                                                            <select id="ultimo_anio" name="ultimo_anio" class="form-control selectpicker show-tick" data-live-search="true" data-size="4" title="A&ntilde;o...">
                                                            </select>
                                                            <span class="text-danger">@error('ultimo_anio') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div id="select_anio" class="col-lg-1 col-md-1 col-sm-3 col-xs-12" style="display: none;">
                                                        <div class="form-group">
                                                            <label class="control-label">A&ntilde;o:</label>
                                                            <span id="p_ultimo_anio" style="color: #898f9d; font-size: 150%; display: block; padding-top: 8px; font-weight: bold;"></span>
                                                            <span class="text-danger">@error('ultimo_anio') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div> --}}

                                                </div>
                                                <div id="valores_acuerdo" class="row" style="display: none;">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <form action="{{ route('acuerdos.create_acuerdo') }}" method="post" id="create-form" desc-to-resolucion-modal="acuerdo de pago" class="create-form">
                                                            @csrf
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
                                                            <div class="form-body">
                                                                <div class="row">
                                                                    <div id="div_acuerdo_numero" class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">N&uacute;mero:</label>
                                                                            <input type="text" id="numero_acuerdo" name="numero_acuerdo" class="form-control" autocomplete="off" placeholder="Autogenerado" value="{{ old('numero_acuerdo') }}" maxlength="10" readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                    <div id="div_fecha_acuerdo" class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Fecha acuerdo:</label>
                                                                            <input type="text" id="fecha_acuerdo" name="fecha_acuerdo" class="form-control datepicker" autocomplete="off" placeholder="Fecha acuerdo" value="{{ old('fecha_acuerdo') }}" style="width: 100%;">
                                                                            <span class="text-danger">@error('fecha_acuerdo') {{ $message }} @enderror</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Vigencia inicial:</label>
                                                                            <select id="anio_inicial_acuerdo" name="anio_inicial_acuerdo" class="form-control" data-size="4" title="Seleccione a&ntilde;o..." data-container="#modal-datos-acuerdo-pago" style="width: 100%">
                                                                            </select>
                                                                            <span class="text-danger">@error('anio_inicial_acuerdo') {{ $message }} @enderror</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Vigencia final:</label>
                                                                            <select id="anio_final_acuerdo" name="anio_final_acuerdo" class="form-control" data-size="4" title="Seleccione a&ntilde;o..." data-container="#modal-datos-acuerdo-pago" style="width: 100%">
                                                                            </select>
                                                                            <span class="text-danger">@error('anio_final_acuerdo') {{ $message }} @enderror</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Cuotas:</label>
                                                                            <input type="text" id="cuotas_acuerdo" name="cuotas_acuerdo" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese cuotas" value="{{ old('cuotas_acuerdo') }}" maxlength="2">
                                                                            <span class="text-danger">@error('cuotas_acuerdo') {{ $message }} @enderror</span>
                                                                        </div>
                                                                    </div>
                                                                    <div id="div_fecha_inicial_acuerdo" class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Fecha inicial pago:</label>
                                                                            <input type="text" id="fecha_inicial_acuerdo" name="fecha_inicial_acuerdo" class="form-control datepicker" autocomplete="off" placeholder="Fecha inicial pago" value="{{ old('fecha_inicial_acuerdo') }}" style="width: 100%;">
                                                                            <span class="text-danger">@error('fecha_inicial_acuerdo') {{ $message }} @enderror</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label for="calcular_intereses_check" class="control-label" style="display: block;">¿Aplicar intereses?</label>
                                                                            <input type="checkbox" id="calcular_intereses" name="calcular_intereses" value="{{ old('calcular_intereses') }}" checked>
                                                                            <span id="span_calcular_intereses" class="text-muted" style="padding-left: 10px;">SI</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">% cuota inicial:</label>
                                                                            <input type="text" id="porcentaje_inicial_acuerdo" name="porcentaje_inicial_acuerdo" class="form-control porcentaje" autocomplete="off" placeholder="Porcentaje cuota inicial" value="{{ old('porcentaje_inicial_acuerdo') }}">
                                                                            <span class="text-danger">@error('porcentaje_inicial_acuerdo') {{ $message }} @enderror</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Valor abono inicial:</label>
                                                                            <input type="text" id="abono_inicial_acuerdo" name="abono_inicial_acuerdo" class="form-control" autocomplete="off" placeholder="Ingrese valor" value="{{ old('abono_inicial_acuerdo') }}">
                                                                            <span class="text-danger">@error('abono_inicial_acuerdo') {{ $message }} @enderror</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Total acuerdo</label>
                                                                            <br />
                                                                            <p id="total_acuerdo" class="text-info" style="font-size: 160%; font-weight: 500; padding-top: 8px; padding-bottom: 0px;" data-total=""></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <div class="form-actions m-t-20">
                                                            <button id="btn_save_create" type="button" class="btn btn-info"> <i class="fa fa-save"></i> Guardar informaci&oacute;n</button>
                                                            <!-- <button type="button" class="btn btn-default">Cancelar</button> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div id="no_acuerdo" class="row acuerdo-no-permission" style="display: none;">
                                                </div> --}}
                                                {{-- <div id="ya_acuerdo" class="row acuerdo-available" style="display: none;">
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <section id="section-bar-2" class="">
                                <div id="div_table" class="row">
                                    <div class="col-lg-12">
                                        <div class="well">
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
                                            <h2 style="width: 100%;">Lista de acuerdos
                                                <button type="button" id="excel_acuerdos" class="btn btn-success pull-right" style="margin-left: 10px;"><i class="fa fa-file-excel-o"></i> Descargar EXCEL</button>
                                                <i style="color: #c0c0c0; float: right; cursor: pointer; margin-top: 10px; margin-right: 10px;" onMouseOver="this.style.color='#14813a'" onMouseOut="this.style.color='#c0c0c0'" class="fa fa-print" id="print_acuerdos" data-toggle="tooltip" title="Imprimir listado"></i>
                                                {{-- <button id="btn_descargar_excel_notas" tipo="notas" type="button" class="btn btn-success pull-right btn_excel"> <i class="fa fa-file-excel-o"></i> Generar EXCEL</button> --}}
                                            </h2>
                                            <table id="acuerdosTable" class="table table-hover table-striped table-bordered">
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="div_edit_form" class="panel panel-info" style="display: none;">
                                    <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Actualizar informaci&oacute;n del acuerdo de pago</div>
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
                                            <form action="{{ route('acuerdos.update_acuerdos') }}" method="post" id="update-form" desc-to-resolucion-modal="acuerdo de pago">
                                                @csrf

                                                <div class="form-body">
                                                    <input type="hidden" id="id_edit" name="id_edit" value="{{ old('id_edit') }}">
                                                    <div class="row">
                                                        <div id="div_acuerdo_numero_edit" class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">N&uacute;mero:</label>
                                                                <input type="text" id="numero_acuerdo_edit" name="numero_acuerdo_edit" class="form-control res-validate" autocomplete="off" placeholder="Autogenerado" value="{{ old('numero_acuerdo_edit') }}" maxlength="10" readonly="readonly">
                                                            </div>
                                                        </div>
                                                        <div id="div_fecha_acuerdo_edit" class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Fecha acuerdo:</label>
                                                                <input type="text" id="fecha_acuerdo_edit" name="fecha_acuerdo_edit" class="form-control datepicker" autocomplete="off" placeholder="Fecha acuerdo" value="{{ old('fecha_acuerdo_edit') }}" style="width: 100%;">
                                                                <span class="text-danger">@error('fecha_acuerdo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Vigencia inicial:</label>
                                                                <select id="anio_inicial_acuerdo_edit" name="anio_inicial_acuerdo_edit" class="form-control" style="width: 100%">
                                                                </select>
                                                                <span class="text-danger">@error('anio_inicial_acuerdo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Vigencia final:</label>
                                                                <select id="anio_final_acuerdo_edit" name="anio_final_acuerdo_edit" class="form-control" style="width: 100%">
                                                                </select>
                                                                <span class="text-danger">@error('anio_final_acuerdo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Cuotas:</label>
                                                                <input type="text" id="cuotas_acuerdo_edit" name="cuotas_acuerdo_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese cuotas" value="{{ old('cuotas_acuerdo_edit') }}" maxlength="2">
                                                                <span class="text-danger">@error('cuotas_acuerdo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div id="div_fecha_inicial_acuerdo_edit" class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Fecha inicial pago:</label>
                                                                <input type="text" id="fecha_inicial_acuerdo_edit" name="fecha_inicial_acuerdo_edit" class="form-control datepicker" autocomplete="off" placeholder="Fecha inicial pago" value="{{ old('fecha_inicial_acuerdo_edit') }}" style="width: 100%;">
                                                                <span class="text-danger">@error('fecha_inicial_acuerdo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="calcular_intereses_check_edit" class="control-label" style="display: block;">¿Aplicar intereses?</label>
                                                                <input type="checkbox" id="calcular_intereses_edit" name="calcular_intereses_edit" value="{{ old('calcular_intereses_edit') }}" checked>
                                                                <span id="span_calcular_intereses_edit" class="text-muted" style="padding-left: 10px;">SI</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">% cuota inicial:</label>
                                                                <input type="text" id="porcentaje_inicial_acuerdo_edit" name="porcentaje_inicial_acuerdo_edit" class="form-control porcentaje" autocomplete="off" placeholder="Porcentaje cuota inicial" value="{{ old('porcentaje_inicial_acuerdo_edit') }}">
                                                                <span class="text-danger">@error('porcentaje_inicial_acuerdo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Valor abono inicial:</label>
                                                                <input type="text" id="abono_inicial_acuerdo_edit" name="abono_inicial_acuerdo_edit" class="form-control" autocomplete="off" placeholder="Ingrese valor" value="{{ old('abono_inicial_acuerdo_edit') }}">
                                                                <span id="abono_inicial_acuerdo_edit-error" class="text-danger"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Total acuerdo</label>
                                                                <br />
                                                                <p id="total_acuerdo_edit" class="text-info accounting" style="font-size: 160%; font-weight: 500; padding-top: 8px; padding-bottom: 0px;" data-total=""></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Archivo resoluci&oacute;n</label><span class="text-muted" style="margin-left: 15px;">(*.pdf)</span>
                                                                <button type="button" class="btn btn-inverse" id="btn_upload_resolucion_edit" style="width: 100%; display: block;"><i class="fa fa-folder-open"></i> Buscar archivo</button>
                                                                <span id="current_filename_edit" class="text-info" style="display: block; padding-top: 5px; font-size: 12px;"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="estado_acuerdo_check_edit" class="control-label" style="display: block;">¿Activo?</label>
                                                                <input type="checkbox" id="estado_acuerdo_edit" name="estado_acuerdo_edit" value="{{ old('estado_acuerdo_edit') }}" checked>
                                                                <span id="span_estado_acuerdo_edit" class="text-muted" style="padding-left: 10px;">SI</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-actions m-t-20">
                                                    <button id="btn_save_edit" type="button" class="btn btn-info" style="display: none;"> <i class="fa fa-save"></i> Actualizar informaci&oacute;n</button>
                                                    <button id="btn_cancel_edit" type="button" class="btn btn-default"> <i class="fa fa-thumbs-down"></i> Cancelar</button>
                                                </div>
                                            </form>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                                                    <hr />
                                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                                        <h3 class="box-title" style="margin: 0; text-align: left;">Discriminaci&oacute;n de cuotas acuerdo de pago:</h3>
                                                        <div style="display: flex; align-items: center; gap: 15px;">
                                                            <span id="lbl_total_seleccionado" style="opacity: 0; transition: opacity 0.3s ease; font-weight: bold; color: #333; font-size: 16px; margin-right: 30px;">
                                                                Total a pagar en factura: <span id="valor_total_seleccionado">$0</span>
                                                            </span>
                                                            <button type="button" id="btn_excel_acuerdo_detalle" class="btn btn-success" style="margin-bottom: 10px; display: none;" data-toggle="tooltip" title="Descargar cuotas en Excel">
                                                                <i class="fa fa-file-excel-o"></i> Descargar EXCEL
                                                            </button>
                                                            <button id="btn_generar_factura_ap" type="button" class="btn btn-danger" style="opacity: 0; transition: opacity 0.3s ease; margin-bottom: 10px; margin-right: 10px;" url="/generate_factura_acuerdo_pdf">
                                                                <i class="fa fa-file-pdf-o"></i> Generar factura AP
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <blockquote style="font-size: 97%; border-left: 5px solid #c9f018 !important;">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                {{-- <p>
                                                                    <strong class="text-info">Discriminaci&oacute;n de cuotas acuerdo de pago:</strong>
                                                                </p> --}}
                                                                <table id="acuerdoDetalleTable" class="table table-hover table-striped table-bordered">
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </blockquote>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
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
{{-- <div id="modal-ver-acuerdo" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-ver-acuerdo-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg"  style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modal-ver-acuerdo-label">Informaci&oacute;n de acuerdo de pago No. <span id="txt_numero_acuerdo"></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">C&oacute;digo predio:</label>
                                        <p id="p_codigo_predio" class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Cuotas:</label>
                                        <p id="p_cuotas_acuerdo" class="form-control-static" style="font-size: 17px; font-weight: bold;"></p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">No. resoluci&oacute;n:</label>
                                        <p id="p_numero_resolucion" class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Resoluci&oacute;n:</label>
                                        <p id="p_file_name" class="form-control-static"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">N&uacute;mero acuerdo:</label>
                                        <p id="p_numero_acuerdo" class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Fecha acuerdo:</label>
                                        <p id="p_fecha_acuerdo" class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Vigencia inicial:</label>
                                        <p id="p_anio_inicial_acuerdo" class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Vigencia final:</label>
                                        <p id="p_anio_final_acuerdo" class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Fecha inic. pago:</label>
                                        <p id="p_fecha_inicial_acuerdo" class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Fecha final pago:</label>
                                        <p id="p_fecha_final_acuerdo" class="form-control-static"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Aplica intereses:</label>
                                        <p id="p_calcular_intereses" class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">% cuota inicial:</label>
                                        <p id="p_porcentaje_inicial_acuerdo" class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Abono inicial:</label>
                                        <p id="p_abono_inicial_acuerdo" class="form-control-static"></p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">Total acuerdo:</label>
                                        <p id="p_total_acuerdo" class="form-control-static"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p>
                                        <strong class="text-info">Discriminaci&oacute;n de cuotas acuerdo de pago:</strong>
                                    </p>
                                    <table id="acuerdoDetalleTable" class="table table-hover table-striped table-bordered">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button type="button" class="btn btn-inverse pull-right btn_pdf" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div> --}}
<div id="modal-impresion" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-impresion-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm" style="width: 25%;">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> --}}
                <h4 class="modal-title" id="modal-impresion-label">Generaci&oacute;n de informe notas a facturas.</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <form id="form-impresion-informe">
                            <div class="form-body">
                                <div class="row">
                                    {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: grid;">
                                        <button id="generar_informe_notas" url="/generate_exenciones_pdf/" type="button" class="btn btn-youtube pull-left btn_pdf"> <i class="fa fa-file-pdf-o btn_pdf"></i> Generar PDF</button>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: grid;">
                                        <button id="btn_descargar_excel_notas" tipo="notas" type="button" class="btn btn-success pull-left btn_excel"> <i class="fa fa-file-excel-o"></i> Generar EXCEL</button>
                                    </div> --}}
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group" style="margin-bottom: 0px; text-align: center;">
                                            <label class="control-label">Fecha m&iacute;nima</label>
                                            <input type="text" id="fecha_min_notas" name="fecha_min_notas" class="form-control datepicker" autocomplete="off" placeholder="Fecha m&iacute;nima" value="{{ old('fecha_min_notas') }}" style="width: 100%;">
                                            <span class="text-danger">@error('fecha_min_notas') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 10px;">
                                        <div class="form-group" style="margin-bottom: 0px; text-align: center;">
                                            <label class="control-label">Fecha m&aacute;xima</label>
                                            <input type="text" id="fecha_max_notas" name="fecha_max_notas" class="form-control datepicker" autocomplete="off" placeholder="Fecha m&aacute;xima" value="{{ old('fecha_max_notas') }}" style="width: 100%;">
                                            <span class="text-danger">@error('fecha_max_notas') {{ $message }} @enderror</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn_descargar_excel_notas" tipo="notas" type="button" class="btn btn-success pull-left btn_excel"> <i class="fa fa-file-excel-o"></i> Generar EXCEL</button>
                <button type="button" class="btn btn-inverse pull-right btn_pdf" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('resolucion')
    @if($opcion->resolucion_elimina == 1 || $opcion->resolucion_edita == 1 || $opcion->resolucion_crea == 1)
        @include('resoluciones.modal')
    @endif
@endsection
