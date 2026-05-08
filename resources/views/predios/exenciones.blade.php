@extends('theme.default')

@section('content')

@push('scripts')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\PrediosExencionesCreateFormRequest', '#create-form'); !!}
    {!! JsValidator::formRequest('App\Http\Requests\PrediosExencionesUpdateFormRequest', '#update-form'); !!}
    <script src="{!! asset('theme/js/accounting.min.js') !!}"></script>
    <script src="{!! asset('theme/js/autonumeric.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/jquery.serializeJSON/jquery.serializejson.min.js') !!}"></script>
    {{-- <script src="{!! asset('theme/js/jquery.inputmask.bundle.min.js') !!}"></script> --}}
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
                                <li id="li-section-bar-1" class="tab-current"><a href="#section-bar-1" class="sticon ti-check-box"><span>Nueva exenci&oacute;n</span></a></li>
                                <li id="li-section-bar-2" class=""><a href="#section-bar-2" class="sticon icon-list"><span>Listado de exenciones</span></a></li>
                                <!-- <li class=""><a href="#section-bar-3" class="sticon ti-stats-up"><span>Analytics</span></a></li>
                                <li class=""><a href="#section-bar-4" class="sticon ti-upload"><span>Upload</span></a></li>
                                <li class=""><a href="#section-bar-5" class="sticon ti-settings"><span>Settings</span></a></li> -->
                            </ul>
                        </nav>
                        <div class="content-wrap">
                            <section id="section-bar-1" class="content-current">
                                <div class="panel panel-inverse">
                                    <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Informaci&oacute;n de la exenci&oacute;n de vigencia</div>
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
                                            <form action="{{ route('prediosexenciones.create_exenciones') }}" method="post" id="create-form" desc-to-resolucion-modal="exenci&oacute;n de vigencia">
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
                                                    <!-- <h3 class="box-title">Informaci&oacute;n de la exenci&oacute;n</h3> -->
                                                    <!-- <hr> -->
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo predio:</label>
                                                                <select id="id_predio" name="id_predio" class="form-control selectpicker show-tick res-validate" data-live-search="true" data-size="4" title="Sin informaci&oacute;n...">
                                                                    @if(count($predios) > 0)
                                                                        @foreach($predios as $predio)
                                                                        <option value="{{ $predio->id }}" {{ old('id_predio') == $predio->id ? 'selected' : '' }}>{{ $predio->codigo_predio }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <span class="text-danger">@error('id_predio') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Concepto predio:</label>
                                                                <select id="id_concepto_predio" name="id_concepto_predio" class="form-control selectpicker show-tick res-validate" data-live-search="true" data-size="4" title="Sin informaci&oacute;n...">
                                                                    @if(count($conceptos_predios) > 0)
                                                                        @foreach($conceptos_predios as $concepto_predio)
                                                                        <option value="{{ $concepto_predio->id }}" {{ old('id_concepto_predio') == $concepto_predio->id ? 'selected' : '' }} data-subtext="{{ $concepto_predio->codigo }}">{{ $concepto_predio->nombre }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <span class="text-danger">@error('id_predio') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Desde:</label>
                                                                <input type="text" id="exencion_desde" name="exencion_desde" class="form-control onlyNumbers res-validate" autocomplete="off" placeholder="Ingrese exenci&oacute;n desde" value="{{ old('exencion_desde') }}" maxlength="4">
                                                                <span class="text-danger">@error('exencion_desde') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Hasta:</label>
                                                                <input type="text" id="exencion_hasta" name="exencion_hasta" class="form-control onlyNumbers res-validate" autocomplete="off" placeholder="Ingrese exenci&oacute;n hasta" value="{{ old('exencion_hasta') }}" maxlength="4">
                                                                <span class="text-danger">@error('exencion_hasta') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Escritura:</label>
                                                                <input type="text" id="escritura" name="escritura" class="form-control onlyNumbers res-validate" autocomplete="off" placeholder="Ingrese escritura" value="{{ old('escritura') }}" maxlength="10">
                                                                <span class="text-danger">@error('escritura') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Matricula:</label>
                                                                <input type="text" id="matricula" name="matricula" class="form-control onlyNumbers res-validate" autocomplete="off" placeholder="Ingrese matricula" value="{{ old('matricula') }}" maxlength="11">
                                                                <span class="text-danger">@error('matricula') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Certificado de libertad:</label>
                                                                <input type="text" id="certificado_libertad" name="certificado_libertad" class="form-control onlyNumbers res-validate" autocomplete="off" placeholder="Ingrese certificado libertad" value="{{ old('certificado_libertad') }}" maxlength="10">
                                                                <span class="text-danger">@error('certificado_libertad') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-actions m-t-20">
                                                    <button id="btn_save_create" type="button" class="btn btn-info"> <i class="fa fa-save"></i> Guardar informaci&oacute;n</button>
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
                                            @if(isset($exenciones))
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
                                                <h2>Lista de exenciones</h2>
                                                <table id="myTable" class="table table-hover table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="cell_center" style="width: 7%;">C&oacute;digo predio</th>
                                                            <th class="cell_center" style="width: 7%;">Desde</th>
                                                            <th class="cell_center" style="width: 7%;">Hasta</th>
                                                            <th class="cell_center" style="width: 10%;">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(count($exenciones) > 0)
                                                            @foreach($exenciones as $exencion)
                                                            <tr style="cursor: pointer;" id="tr_exencion_{{ $exencion->id }}" json-data='@json($exencion)'>
                                                                <td class="edit_row cell_center">{{ $exencion->codigo_predio }}</td>
                                                                <td class="edit_row cell_center">{{ $exencion->exencion_desde }}</td>
                                                                <td class="edit_row cell_center">{!! $exencion->exencion_hasta !!}</td>
                                                                <td class="cell_center">
                                                                    <button type="button" ide="{{ $exencion->id }}" class="modify_row btn btn-info" req_res="{{ $opcion->resolucion_edita }}"><i class="fa fa-pencil-square"></i></button>
                                                                    &nbsp;&nbsp;
                                                                    <button type="button" ide="{{ $exencion->id }}" class="delete_row btn btn-inverse" req_res="{{ $opcion->resolucion_elimina }}" msg="¿Está seguro/a que desea anular la exenci&oacute;n del predio?"><i class="fa fa-trash-o"></i></button>
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
                                                    {{ $predios->links('layouts.paginationlinks') }}
                                                </div> --}}
                                                <form id="form_delete" action="{{ route('prediosexenciones.delete_exenciones') }}" method="post" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" id="input_delete" name="input_delete">
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div id="div_edit_form" class="panel panel-info" style="display: none; margin-bottom: 0px">
                                    <div class="panel-heading">
                                        <i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Actualizar informaci&oacute;n de la exenci&oacute;n
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
                                            <form action="{{ route('prediosexenciones.update_exenciones') }}" method="post" id="update-form" desc-to-resolucion-modal="exenci&oacute;n de vigencia">
                                                @csrf

                                                <div class="form-body">
                                                    <input type="hidden" id="id_edit" name="id_edit" value="{{ old('id_edit') }}">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo predio:</label>
                                                                <input type="hidden" id="id_predio_edit" name="id_predio_edit" value="{{ old('id_predio_edit') }}">
                                                                <input type="text" id="codigo_predio_edit" name="codigo_predio_edit" class="form-control" placeholder="Ingrese c&oacute;digo" value="{{ old('codigo_predio_edit') }}" readonly="readonly">
                                                                {{-- <span class="text-danger">@error('id_predio_edit') {{ $message }} @enderror</span> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Desde:</label>
                                                                <input type="text" id="exencion_desde_edit" name="exencion_desde_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese exenci&oacute;n desde" value="{{ old('exencion_desde_edit') }}" maxlength="4">
                                                                <span class="text-danger">@error('exencion_desde_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Hasta:</label>
                                                                <input type="text" id="exencion_hasta_edit" name="exencion_hasta_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese exenci&oacute;n hasta" value="{{ old('exencion_hasta_edit') }}" maxlength="4">
                                                                <span class="text-danger">@error('exencion_hasta_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Escritura:</label>
                                                                <input type="text" id="escritura_edit" name="escritura_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese escritura" value="{{ old('escritura_edit') }}" maxlength="10">
                                                                <span class="text-danger">@error('escritura_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Matricula:</label>
                                                                <input type="text" id="matricula_edit" name="matricula_edit" class="form-control" autocomplete="off" placeholder="Ingrese matricula" value="{{ old('matricula_edit') }}">
                                                                <span class="text-danger">@error('matricula_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Certificado de libertad:</label>
                                                                <input type="text" id="certificado_libertad_edit" name="certificado_libertad_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese certificado libertad" value="{{ old('certificado_libertad_edit') }}" maxlength="10">
                                                                <span class="text-danger">@error('certificado_libertad_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-actions m-t-20">
                                                    <button id="btn_save_edit" type="button" class="btn btn-info"> <i class="fa fa-save"></i> Actualizar informaci&oacute;n</button>
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

@section('buttons')
@endsection

@section('modales')
@endsection

@section('resolucion')
    @if($opcion->resolucion_elimina == 1 || $opcion->resolucion_edita == 1 || $opcion->resolucion_crea == 1)
        @include('resoluciones.modal')
    @endif
@endsection
