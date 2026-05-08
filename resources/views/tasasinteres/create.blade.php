@extends('theme.default')
@section('content')

@push('scripts')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\TasasInteresCreateFormRequest', '#create-form'); !!}
    {!! JsValidator::formRequest('App\Http\Requests\TasasInteresUpdateFormRequest', '#update-form'); !!}
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
                                <li id="li-section-bar-1" class="tab-current"><a href="#section-bar-1" class="sticon ti-pulse"><span>Nueva tasa de inter&eacute;s</span></a></li>
                                <li id="li-section-bar-2" class=""><a href="#section-bar-2" class="sticon icon-list"><span>Listado de tasas de inter&eacute;s</span></a></li>
                            </ul>
                        </nav>
                        <div class="content-wrap">
                            <section id="section-bar-1" class="content-current">
                                <div class="panel panel-inverse">
                                    <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Informaci&oacute;n de la tasa de inter&eacute;s</div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <form action="{{ route('tasasinteres.create_tasasinteres') }}" method="post" id="create-form">
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
                                                    <!-- <h3 class="box-title">Informaci&oacute;n de la tasa de inter&eacute;s</h3> -->
                                                    <!-- <hr> -->
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">A&ntilde;o</label>
                                                                <input type="text" id="anio" name="anio" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese a&ntilde;o" value="{{ old('anio') }}" maxlength="4">
                                                                <span class="text-danger">@error('anio') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Mes</label>
                                                                <input type="text" id="mes" name="mes" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese mes" value="{{ old('mes') }}" maxlength="2">
                                                                <span class="text-danger">@error('mes') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tasa diaria:</label>
                                                                <input type="text" id="tasa_diaria" name="tasa_diaria" class="form-control tasa" autocomplete="off" placeholder="Ingrese tasa diaria" value="{{ old('tasa_diaria') }}">
                                                                <span class="text-danger">@error('tasa_diaria') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tasa mensual:</label>
                                                                <input type="text" id="tasa_mensual" name="tasa_mensual" class="form-control tasa" autocomplete="off" placeholder="Ingrese tasa mensual" value="{{ old('tasa_mensual') }}">
                                                                <span class="text-danger">@error('tasa_mensual') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tasa acuerdo de pago:</label>
                                                                <input type="text" id="tasa_acuerdo" name="tasa_acuerdo" class="form-control tasa" autocomplete="off" placeholder="Ingrese tasa acuerdo de pago" value="{{ old('tasa_acuerdo') }}">
                                                                <span class="text-danger">@error('tasa_acuerdo') {{ $message }} @enderror</span>
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
                                            @if(isset($tasas_interes))
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
                                                <h2>Lista de tasas de inter&eacute;s</h2>
                                                <table id="myTable" class="table table-hover table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="cell_center" style="width: 7%;">A&ntilde;o</th>
                                                            <th class="cell_center" style="width: 7%;">Mes</th>
                                                            <th class="cell_center">Tasa diaria</th>
                                                            <th class="cell_center">Tasa mensual</th>
                                                            <th class="cell_center">Tasa acuerdo</th>
                                                            <th class="cell_center" style="width: 10%;">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(count($tasas_interes) > 0)
                                                            @foreach($tasas_interes as $tasa_interes)
                                                            <tr style="cursor: pointer;" json-data='@json($tasa_interes)'>
                                                                <td class="edit_row cell_center">{{ $tasa_interes->anio }}</td>
                                                                <td class="edit_row cell_center">{{ $tasa_interes->mes }}</td>
                                                                <td class="edit_row cell_center">{{ $tasa_interes->tasa_diaria }}</td>
                                                                <td class="edit_row cell_center">{{ $tasa_interes->tasa_mensual }}</td>
                                                                <td class="edit_row cell_center">{{ $tasa_interes->tasa_acuerdo }}</td>
                                                                <td class="cell_center">
                                                                    <button type="button" ide="{{ $tasa_interes->id }}" class="modify_row btn btn-info"><i class="fa fa-pencil-square"></i></button>
                                                                    &nbsp;&nbsp;
                                                                    <button type="button" ide="{{ $tasa_interes->id }}" class="delete_row btn btn-inverse"><i class="fa fa-trash-o"></i></button>
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
                                                    {{ $tasas_interes->links('layouts.paginationlinks') }}
                                                </div> --}}
                                                <form id="form_delete" action="{{ route('tasasinteres.delete_tasasinteres') }}" method="post" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" id="input_delete" name="input_delete">
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div id="div_edit_form" class="panel panel-info" style="display: none;">
                                    <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Actualizar informaci&oacute;n de la tasa de inter&eacute;s</div>
                                    <div class="panel-wrapper collapse in" aria-expanded="true">
                                        <div class="panel-body">
                                            <form action="{{ route('tasasinteres.update_tasasinteres') }}" method="post" id="update-form">
                                                @csrf

                                                <div class="form-body">
                                                    <input type="hidden" id="id_edit" name="id_edit" value="{{ old('id_edit') }}">
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">A&ntilde;o</label>
                                                                <input type="text" id="anio_edit" name="anio_edit" class="form-control onlyNumbers" placeholder="Ingrese a&ntilde;o" value="{{ old('anio_edit') }}" readonly="readonly">
                                                                <span class="text-danger">@error('anio_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Mes</label>
                                                                <input type="text" id="mes_edit" name="mes_edit" class="form-control onlyNumbers" autocomplete="off" placeholder="Ingrese mes" value="{{ old('mes_edit') }}" readonly="readonly">
                                                                <span class="text-danger">@error('mes_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tasa diaria:</label>
                                                                <input type="text" id="tasa_diaria_edit" name="tasa_diaria_edit" class="form-control tasa" autocomplete="off" placeholder="Ingrese tasa diaria" value="{{ old('tasa_diaria_edit') }}">
                                                                <span class="text-danger">@error('tasa_diaria_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tasa mensual:</label>
                                                                <input type="text" id="tasa_mensual_edit" name="tasa_mensual_edit" class="form-control tasa" autocomplete="off" placeholder="Ingrese tasa mensual" value="{{ old('tasa_mensual_edit') }}">
                                                                <span class="text-danger">@error('tasa_mensual_edit') {{ $message }} @enderror</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tasa acuerdo de pago:</label>
                                                                <input type="text" id="tasa_acuerdo_edit" name="tasa_acuerdo_edit" class="form-control tasa" autocomplete="off" placeholder="Ingrese tasa acuerdo de pago" value="{{ old('tasa_acuerdo_edit') }}">
                                                                <span class="text-danger">@error('tasa_acuerdo_edit') {{ $message }} @enderror</span>
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
