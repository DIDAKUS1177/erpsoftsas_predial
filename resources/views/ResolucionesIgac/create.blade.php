@extends('theme.default')
@section('content')

@push('scripts')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\ResolucionesIgacCreateFormRequest', '#create-form'); !!}
    {!! JsValidator::formRequest('App\Http\Requests\ResolucionesIgacUpdateFormRequest', '#update-form'); !!}
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
                            <li id="li-section-bar-1" class="tab-current"><a href="#section-bar-1" class="sticon ti-pulse"><span>Nueva resoluci&oacute;n Igac</span></a></li>
                                <li id="li-section-bar-2" class=""><a href="#section-bar-2" class="sticon icon-list"><span>Lista resoluciones Igac</span></a></li>
                                <!-- <li class=""><a href="#section-bar-3" class="sticon ti-stats-up"><span>Analytics</span></a></li>
                                <li class=""><a href="#section-bar-4" class="sticon ti-upload"><span>Upload</span></a></li>
                                <li class=""><a href="#section-bar-5" class="sticon ti-settings"><span>Settings</span></a></li> -->
                            </ul>
                        </nav>
                        <div class="content-wrap">
                            <section id="section-bar-1" class="content-current">
                                <div class="panel panel-inverse">
                                <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Informaci&oacute;n de resoluci&oacute;n Igac</div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                        <form action="{{ route('resoluciones_igac.create_resolucion_igac') }}" method="post" id="create-form">
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
                                                    <!-- <h3 class="box-title">Informaci&oacute;n del tipo de predio</h3> -->
                                                    <!-- <hr> -->
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">A&ntilde;o</label>
                                                                <input type="text" id="ano" name="ano" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese a&ntilde;o" value="{{ old('ano') }}" maxlength="4">
                                                                <span class="text-danger">@error('ano') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Resoluci&oacute;n</label>
                                                                <input type="text" id="resolucion" name="resolucion" class="form-control" autocomplete="off" placeholder="Ingrese resoluci&oacute;n" value="{{ old('resolucion') }}" maxlength="30">
                                                                <span class="text-danger">@error('resolucion') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Fecha</label>
                                                                <input type="text" id="fecha" name="fecha" class="form-control datepicker" autocomplete="off" placeholder="Ingrese fecha" value="{{ old('fecha') }}" maxlength="30">
                                                                <span class="text-danger">@error('fecha') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Consecutivo</label>
                                                                <input type="text" id="consecutivo" name="consecutivo" class="form-control uppercase" autocomplete="off" placeholder="Ingrese consecutivo" value="{{ old('consecutivo') }}" maxlength="5">
                                                                <span class="text-danger">@error('consecutivo') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo</label>
                                                                <input type="text" id="codigo" name="codigo" class="form-control" autocomplete="off" placeholder="Ingrese c&oacute;digo" value="{{ old('codigo') }}" maxlength="30">
                                                                <span class="text-danger">@error('codigo') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo anterior</label>
                                                                <input type="text" id="codigoanterio" name="codigoanterior" class="form-control" autocomplete="off" placeholder="Ingrese c&oacute;digo anterior" value="{{ old('codigoanterior') }}" maxlength="30">
                                                                <span class="text-danger">@error('codigoanterior') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Tipo</label>
                                                                <input type="text" id="tipo" name="tipo" class="form-control uppercase" autocomplete="off" placeholder="Ingrese tipo" value="{{ old('tipo') }}" maxlength="30">
                                                                <span class="text-danger">@error('tipo') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Tipo registro</label>
                                                                <input type="text" id="tiporegistro" name="tiporegistro" class="form-control" autocomplete="off" placeholder="Ingrese tipo registro" value="{{ old('tiporegistro') }}" maxlength="30">
                                                                <span class="text-danger">@error('tiporegistro') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">N&uacute;mero orden</label>
                                                                <input type="text" id="numeroorden" name="numeroorden" class="form-control" autocomplete="off" placeholder="Ingrese n&uacute;mero orden" value="{{ old('numeroorden') }}" maxlength="30">
                                                                <span class="text-danger">@error('numeroorden') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                    <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Aval&uacute;o</label>
                                                                <input type="text" id="avaluoigac" name="avaluoigac" class="form-control" autocomplete="off" placeholder="Ingrese aval&uacute;o" value="{{ old('avaluoigac') }}" maxlength="30">
                                                                <span class="text-danger">@error('avaluoigac') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                         <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">&Aacute;rea</label>
                                                                <input type="text" id="area" name="area" class="form-control" autocomplete="off" placeholder="Ingrese &aacute;rea" value="{{ old('area') }}" maxlength="30">
                                                                <span class="text-danger">@error('area') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre</label>
                                                                <input type="text" id="nombre" name="nombre" class="form-control" autocomplete="off" placeholder="Ingrese nombre" value="{{ old('nombre') }}" maxlength="30">
                                                                <span class="text-danger">@error('nombre') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
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
                                            @if(isset($resoluciones_igac))
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
                                                <h2>Lista de resoluciones Igac</h2>
                                                <table id="myTable" class="table table-hover table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="cell_center">A&ntilde;o</th>
                                                            <th class="cell_center">Resoluci&oacute;n</th>
                                                            <th class="cell_center">Fecha</th>
                                                            <th class="cell_center">Consecutivo</th>
                                                            <th class="cell_center">C&oacute;digo</th>
                                                            <th class="cell_center">C&oacute;digo nnterior</th>
                                                            <th class="cell_center">Tipo</th>
                                                            <th class="cell_center">Tipo registro</th>
                                                            <th class="cell_center">N&uacute;mero orden</th>
                                                            <th class="cell_center">Aval&uacute;o</th>
                                                            <th class="cell_center">&Aacute;rea</th>
                                                            <th class="cell_center">Nombre</th>
                                                            <th class="cell_center">Opciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(count($resoluciones_igac) > 0)
                                                            @foreach($resoluciones_igac as $resolucion_igac)
                                                            <tr style="cursor: pointer;" json-data='@json($resolucion_igac)'>
                                                                <td class="cell_center edit_row">{{ $resolucion_igac->ano }}</td>
                                                                <td class="cell_center edit_row">{{ $resolucion_igac->resolucion }}</td>
                                                                <td class="edit_row cell_center">{{ $resolucion_igac->fecha }}</td>
                                                                <td class="edit_row">{{ $resolucion_igac->consecutivo }}</td>
                                                                <td class="edit_row">{{ $resolucion_igac->codigo }}</td>
                                                                <td class="edit_row">{{ $resolucion_igac->codigoanterior }}</td>
                                                                <td class="edit_row">{{ $resolucion_igac->tipo }}</td>
                                                                <td class="edit_row">{{ $resolucion_igac->tiporegistro }}</td>
                                                                <td class="edit_row">{{ $resolucion_igac->numeroorden }}</td>
                                                                <td class="edit_row">{{ $resolucion_igac->avaluoigac }}</td>
                                                                <td class="edit_row">{{ $resolucion_igac->area }}</td>
                                                                <td class="edit_row">{{ $resolucion_igac->nombre }}</td>
                                                                <td class="cell_center">
                                                                    <button type="button" ide="{{ $resolucion_igac->id }}" class="modify_row btn btn-info"><i class="fa fa-pencil-square"></i></button>
                                                                    &nbsp;&nbsp;
                                                                    <button type="button" ide="{{ $resolucion_igac->id }}" class="delete_row btn btn-inverse"><i class="fa fa-trash-o"></i></button>
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
                                                    {{ $resoluciones_igac->links('layouts.paginationlinks') }}
                                                </div> --}}
                                                <form id="form_delete" action="{{ route('resoluciones_igac.delete_resolucion_igac') }}" method="post" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" id="input_delete" name="input_delete">
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div id="div_edit_form" class="panel panel-info" style="display: none;">
                                    <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Actualizar informaci&oacute;n de resoluci&oacute;n Igac</div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <form action="{{ route('resoluciones_igac.update_resolucion_igac') }}" method="post" id="update-form">


                                                <div class="form-body">
                                                    <input type="hidden" id="id_edit" name="id_edit" value="{{ old('id_edit') }}">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">A&ntilde;o</label>
                                                                <input type="text" id="ano_edit" name="ano_edit" class="form-control uppercase" autocomplete="off" placeholder="Ingrese a&ntilde;o" value="{{ old('ano_edit') }}" maxlength="10">
                                                                <span class="text-danger">@error('ano_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Resoluci&oacute;n</label>
                                                                <input type="text" id="resolucion_edit" name="resolucion_edit" class="form-control" autocomplete="off" placeholder="Ingrese resoluci&oacute;n" value="{{ old('resolucion_edit') }}" maxlength="30">
                                                                <span class="text-danger">@error('resolucion_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Fecha</label>
                                                                <input type="text" id="fecha_edit" name="fecha_edit" class="form-control" autocomplete="off" placeholder="Ingrese fecha" value="{{ old('fecha_edit') }}" maxlength="30">
                                                                <span class="text-danger">@error('fecha_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Consecutivo</label>
                                                                <input type="text" id="consecutivo_edit" name="consecutivo_edit" class="form-control uppercase" autocomplete="off" placeholder="Ingrese consecutivo" value="{{ old('consecutivo_edit') }}" maxlength="5">
                                                                <span class="text-danger">@error('consecutivo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo</label>
                                                                <input type="text" id="codigo_edit" name="codigo_edit" class="form-control" autocomplete="off" placeholder="Ingrese c&oacute;digo" value="{{ old('codigo_edit') }}" maxlength="30">
                                                                <span class="text-danger">@error('codigo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">C&oacute;digo anterior</label>
                                                                <input type="text" id="codigoanterio_edit" name="codigoanterior_edit" class="form-control" autocomplete="off" placeholder="Ingrese c&oacute;digo anterior" value="{{ old('codigoanterior_edit') }}" maxlength="30">
                                                                <span class="text-danger">@error('codigoanterior_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Tipo</label>
                                                                <input type="text" id="tipo_edit" name="tipo_edit" class="form-control uppercase" autocomplete="off" placeholder="Ingrese tipo" value="{{ old('tipo_edit') }}" maxlength="30">
                                                                <span class="text-danger">@error('tipo_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Tipo registro</label>
                                                                <input type="text" id="tiporegistro_edit" name="tiporegistro_edit" class="form-control" autocomplete="off" placeholder="Ingrese tipo registro" value="{{ old('tiporegistro_edit') }}" maxlength="30">
                                                                <span class="text-danger">@error('tiporegistro_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">N&uacute;mero orden</label>
                                                                <input type="text" id="numeroorden_edit" name="numeroorden_edit" class="form-control" autocomplete="off" placeholder="Ingrese n&uacute;mero orden" value="{{ old('numeroorden_edit') }}" maxlength="30">
                                                                <span class="text-danger">@error('numeroorden_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                    <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Aval&uacute;o</label>
                                                                <input type="text" id="avaluoigac_edit" name="avaluoigac_edit" class="form-control" autocomplete="off" placeholder="Ingrese aval&uacute;o" value="{{ old('avaluoigac_edit') }}" maxlength="30">
                                                                <span class="text-danger">@error('avaluoigac_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                         <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">&Aacute;rea</label>
                                                                <input type="text" id="area_edit" name="area_edit" class="form-control" autocomplete="off" placeholder="Ingrese &aacute;rea" value="{{ old('area_edit') }}" maxlength="30">
                                                                <span class="text-danger">@error('area_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre</label>
                                                                <input type="text" id="nombre_edit" name="nombre_edit" class="form-control" autocomplete="off" placeholder="Ingrese nombre" value="{{ old('nombre_edit') }}" maxlength="30">
                                                                <span class="text-danger">@error('nombre_edit') {{ $message }} @enderror</span>
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
