@extends('theme.default')

@section('content')

@push('scripts')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\NotasCreateFormRequest', '#create-form'); !!}
    {!! JsValidator::formRequest('App\Http\Requests\NotasUpdateFormRequest', '#update-form'); !!}
    <script src="{!! asset('theme/js/accounting.min.js') !!}"></script>
    <script src="{!! asset('theme/js/autonumeric.min.js') !!}"></script>
    <script src="{!! asset('theme/js/jquery.form.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/jquery.serializeJSON/jquery.serializejson.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/blockUI/jquery.blockUI.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/bootstrap-filestyle/bootstrap-filestyle.min.js') !!}"></script>
    <script src="{!! asset('theme/js/customjs/controlsite.js') !!}"></script>
    <script src="{!! asset('theme/js/customjs/load_file.js') !!}"></script>
    <script src="{!! asset('theme/js/customjs/notas.js') !!}"></script>
@endpush
@if(Session::get('tab_current'))
<input type="hidden" id="tab" value="{{ Session::get('tab_current') }}">
@elseif($tab_current)
<input type="hidden" id="tab" value="{{ $tab_current }}">
@endif
<input type="hidden" id="opcion" value='@json($opcion)'>
<input type="hidden" id="interfaz" value='notas'>
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
                                <li id="li-section-bar-1" class="tab-current"><a href="#section-bar-1" class="sticon ti-check-box"><span>Nueva nota a factura</span></a></li>
                                <li id="li-section-bar-2" class=""><a href="#section-bar-2" class="sticon icon-list"><span>Listado de notas</span></a></li>
                                <!-- <li class=""><a href="#section-bar-3" class="sticon ti-stats-up"><span>Analytics</span></a></li>
                                <li class=""><a href="#section-bar-4" class="sticon ti-upload"><span>Upload</span></a></li>
                                <li class=""><a href="#section-bar-5" class="sticon ti-settings"><span>Settings</span></a></li> -->
                            </ul>
                        </nav>
                        <div class="content-wrap" style="min-height: 420px;">
                            <section id="section-bar-1" class="content-current">
                                <div class="panel panel-inverse">
                                    <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Informaci&oacute;n de la nota a factura</div>
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
                                                <!-- <h3 class="box-title">Informaci&oacute;n de la nota a factura</h3> -->
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
                                                    <div id="select_factura" class="col-lg-2 col-md-2 col-sm-3 col-xs-12" style="display: none;">
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
                                                    </div>
                                                    <div id="load_resolucion" class="col-lg-offset-1 col-lg-5 col-md-5 col-sm-6 col-xs-12" style="display: none;">
                                                        {{-- <input type="hidden" id="filename" value=""> --}}
                                                        {{-- <input type="hidden" id="fileid" value=""> --}}
                                                        <form id="load-form" action="{{route('upload-file-resolucion')}}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="form-body">
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Archivo resoluci&oacute;n</label><span class="text-muted" style="margin-left: 15px;">(*.pdf)</span>
                                                                            <input type="file" accept=".pdf" id="file" name="file" class="form-control filestyle" data-placeholder="" value="{{ old('file') }}" data-buttonName="btn-inverse" data-buttonBefore="true" data-buttonText="Buscar archivo">
                                                                            <span class="text-danger">@error('file') {{ $message }} @enderror</span>
                                                                        </div>
                                                                        <div class="progress">
                                                                            <div class="bar one"></div >
                                                                            <div class="porciento one">0%</div >
                                                                        </div>
                                                                        <span id="error_fileupload" class="text-danger" style="display: none;">Max file size 10 Mb</span>
                                                                        <span id="current_filename" class="text-inverse" style="display: block; padding-bottom: 15px;"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <button id="btn_cargar_archivo_resolucion" type="button" style="display: none;">Cargar archivo</button>
                                                    </div>
                                                </div>
                                                <div id="valores_factura" class="row" style="display: none;">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <form action="{{ route('notas.create_nota_factura') }}" method="post" id="create-form" desc-to-resolucion-modal="nota de factura" class="create-form">
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
                                                            <input type="hidden" id="file_name" name="file_name" value="">
                                                            <div class="row">
                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Impuesto predial:</label>
                                                                        <input id="valor_concepto1" name="valor_concepto1" class="form-control negativos conceptos res-validate" autocomplete="off" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Intereses predial:</label>
                                                                        <input id="valor_concepto2" name="valor_concepto2" class="form-control negativos conceptos res-validate" autocomplete="off" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Impuesto CAR:</label>
                                                                        <input id="valor_concepto3" name="valor_concepto3" class="form-control negativos conceptos res-validate" autocomplete="off" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Intereses CAR:</label>
                                                                        <input id="valor_concepto4" name="valor_concepto4" class="form-control negativos conceptos res-validate" autocomplete="off" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Descuento predial:</label>
                                                                        <input id="valor_concepto13" name="valor_concepto13" class="form-control negativos conceptos res-validate" autocomplete="off" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Sobretasa predial:</label>
                                                                        <input id="valor_concepto14" name="valor_concepto14" class="form-control negativos conceptos res-validate" autocomplete="off" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Descuento CAR:</label>
                                                                        <input id="valor_concepto15" name="valor_concepto15" class="form-control negativos conceptos res-validate" autocomplete="off" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Sobretasa bomberil:</label>
                                                                        <input id="valor_concepto16" name="valor_concepto16" class="form-control negativos conceptos res-validate" autocomplete="off" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Saldos a favor y dev...:</label>
                                                                        <input id="valor_concepto17" name="valor_concepto17" class="form-control negativos conceptos res-validate" autocomplete="off" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Alumbrado p&uacute;blico:</label>
                                                                        <input id="valor_concepto18" name="valor_concepto18" class="form-control negativos conceptos res-validate" autocomplete="off" value="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Total factura:</label>
                                                                        <input id="total_calculo" name="total_calculo" class="form-control negativos res-validate" autocomplete="off" value="" readonly="readonly" style="font-size: xx-large; font-weight: bold; padding: 5px; height: auto; width: initial; text-align: center;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <div class="form-actions m-t-20">
                                                            <button id="btn_save_create" type="button" class="btn btn-info" disabled="disabled"> <i class="fa fa-save"></i> Guardar informaci&oacute;n</button>
                                                            <!-- <button type="button" class="btn btn-default">Cancelar</button> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <section id="section-bar-2" class="">
                                <div id="div_table" class="row">
                                    <div class="col-lg-12">
                                        <div class="well">
                                            <h2 style="width: 100%;">Lista de notas a facturas
                                                <i style="color: #c0c0c0; float: right; cursor: pointer;" onMouseOver="this.style.color='#14813a'" onMouseOut="this.style.color='#c0c0c0'" class="fa fa-print" id="print_notas"></i>
                                                {{-- <button id="btn_descargar_excel_notas" tipo="notas" type="button" class="btn btn-success pull-right btn_excel"> <i class="fa fa-file-excel-o"></i> Generar EXCEL</button> --}}
                                            </h2>
                                            <table id="notasTable" class="table table-hover table-striped table-bordered">
                                            </table>
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
<div id="modal-ver-nota" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="modal-ver-nota-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg"  style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modal-ver-nota-label">Informaci&oacute;n de nota a factura</span></h4>
                {{-- <span id="txt_factura_pago"></span> a&ntilde;o <span id="txt_anio"> --}}
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">C&oacute;digo predio:</label>
                                        <div class="col-md-8">
                                            <p id="p_codigo_predio" class="form-control-static"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Factura:</label>
                                        <div class="col-md-8">
                                            <p id="p_factura_pago" class="form-control-static"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">A&ntilde;o:</label>
                                        <div class="col-md-8">
                                            <p id="p_anio" class="form-control-static"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Usuario:</label>
                                        <div class="col-md-8">
                                            <p id="p_usuario" class="form-control-static"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Fecha creaci&oacute;n:</label>
                                        <div class="col-md-8">
                                            <p id="p_fecha_creacion" class="form-control-static"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Resoluci&oacute;n:</label>
                                        <div class="col-md-8">
                                            <p id="p_resolucion" class="form-control-static"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table id="table_notas" class="table table-hover table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Concepto</th>
                                                <th>Detalle de nota</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_notas">
                                        </tbody>
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
</div>
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
