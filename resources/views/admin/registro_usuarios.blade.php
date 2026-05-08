@extends('theme.default')
@section('content')

@push('scripts')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\UsuariosCreateFormRequest', '#create-form'); !!}
    {!! JsValidator::formRequest('App\Http\Requests\UsuariosUpdateFormRequest', '#update-form'); !!}
    <script src="{!! asset('theme/js/customjs/controlsite.js') !!}"></script>
@endpush
@if(Session::get('tab_current'))
<input type="hidden" id="tab" value="{{ Session::get('tab_current') }}">
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
                            <li id="li-section-bar-1" class="tab-current"><a href="#section-bar-1" class="sticon ti-user"><span>Nuevo usuario</span></a></li>
                            <li id="li-section-bar-2" class=""><a href="#section-bar-2" class="sticon icon-list"><span>Listado de usuarios</span></a></li>
                            <!-- <li class=""><a href="#section-bar-3" class="sticon ti-stats-up"><span>Analytics</span></a></li>
                            <li class=""><a href="#section-bar-4" class="sticon ti-upload"><span>Upload</span></a></li>
                            <li class=""><a href="#section-bar-5" class="sticon ti-settings"><span>Settings</span></a></li> -->
                        </ul>
                    </nav>
                    <div class="content-wrap">
                        <section id="section-bar-1" class="content-current">
                            <div class="panel panel-inverse">
                                <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Informaci&oacute;n del usuario</div>
                                <div class="panel-wrapper collapse in" aria-expanded="true">
                                    <div class="panel-body">
                                        <form action="{{ route('admin.create_usu') }}" method="post" id="create-form">
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
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Nombres</label>
                                                            <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Ingrese nombres" value="{{ old('nombres') }}">
                                                            <span class="text-danger">@error('nombres') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Apellidos</label>
                                                            <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Ingrese apellidos" value="{{ old('apellidos') }}">
                                                            <span class="text-danger">@error('apellidos') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Tipo identificaci&oacute;n</label>
                                                            <select id="id_tipo_identificacion" name="id_tipo_identificacion" class="form-control selectpicker show-tick" title="Seleccione...">
                                                                <option value="1" {{ old('id_tipo_identificacion') == "1"  || old('id_tipo_identificacion') == null ? 'selected' : '' }}>C&eacute;dula</option>
                                                                <option value="2" {{ old('id_tipo_identificacion') == "2" ? 'selected' : '' }}>Tarjeta Identidad</option>
                                                                <option value="3" {{ old('id_tipo_identificacion') == "3" ? 'selected' : '' }}>Pasaporte</option>
                                                                <option value="4" {{ old('id_tipo_identificacion') == "4" ? 'selected' : '' }}>C&eacute;dula extranjer&iacute;a</option>
                                                            </select>
                                                            <span class="text-danger">@error('id_tipo_identificacion') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Identificaci&oacute;n</label>
                                                            <input type="text" id="documento" name="documento" class="form-control onlyNumbers" placeholder="Ingrese identificaci&oacute;n" value="{{ old('documento') }}">
                                                            <span class="text-danger">@error('documento') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Correo electr&oacute;nico</label>
                                                            <input type="email" id="correo_electronico" name="correo_electronico" class="form-control" placeholder="Ingrese correo electr&oacute;nico" value="{{ old('correo_electronico') }}">
                                                            <span class="text-danger">@error('correo_electronico') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Fecha nacimiento</label>
                                                            <div style="position:relative">
                                                                <div class="input-group">
                                                                    <input type="text" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control datepicker withadon" autocomplete="off" placeholder="yyyy-mm-dd" value="{{ old('fecha_nacimiento') }}">
                                                                    <div class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-th"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <span class="text-danger">@error('fecha_nacimiento') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Tel&eacute;fono</label>
                                                            <input type="text" id="telefono" name="telefono" class="form-control onlyNumbers" placeholder="Ingrese tel&eacute;fono" value="{{ old('telefono') }}">
                                                            <span class="text-danger">@error('telefono') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-10 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Direcci&oacute;n</label>
                                                            <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Ingrese direcci&oacute;n" value="{{ old('direccion') }}">
                                                            <span class="text-danger">@error('direccion') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="password" class="control-label">Contrase&ntilde;a:</label>
                                                            <div class="input-group">
                                                                <input type="password" class="form-control withadon" id="password" name="password" value="{{ old('password') }}">
                                                                <div class="input-group-addon"><i class="ti-lock"></i></div>
                                                            </div>
                                                            <span class="text-danger">@error('password') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="password_confirmation" class="control-label">Repita la contrase&ntilde;a:</label>
                                                            <div class="input-group">
                                                                <input type="password" class="form-control withadon" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}">
                                                                <div class="input-group-addon"><i class="ti-lock"></i></div>
                                                            </div>
                                                            <span class="text-danger">@error('password_confirmation') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Tipo usuario</label>
                                                            <select id="id_tipo_usuario" name="id_tipo_usuario" class="form-control selectpicker show-tick dropdown" data-dropup-auto="false" data-live-search="true" data-size="3" title="Seleccione...">
                                                                @if(count($tipos_usuarios) > 0)
                                                                    @foreach($tipos_usuarios as $tipo_usuario)
                                                                        <option value="{{ $tipo_usuario->id }}" {{ old('id_tipo_usuario') == $tipo_usuario->id ? 'selected' : '' }}>{{ $tipo_usuario->descripcion }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <span class="text-danger">@error('id_tipo_usuario') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Estado</label>
                                                            <input id="estado" name="estado" type="hidden" value="A">
                                                            <div id="div_estado_tmp" class="checkbox checkbox-danger">
                                                                <input id="estado_tmp" name="estado_tmp" type="checkbox" checked="">
                                                                <label id="label_estado_tmp" for="estado_tmp"> Activo </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions m-t-20">
                                                <button type="submit" class="btn btn-info"> <i class="fa fa-save"></i> Guardar informaci&oacute;n del usuario</button>
                                                <!-- <button type="button" class="btn btn-default">Cancelar</button> -->
                                            </div>
                                        </form>
                                        <br />
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section id="section-bar-2" class="">
                            <div id="div_table" class="row">
                                <div class="col-lg-12">
                                    <div class="well">
                                        @if(isset($usuarios))
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
                                            <h2>Lista de usuarios</h2>
                                            <table id="myTable" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="cell_center">Identificaci&oacute;n</th>
                                                        {{-- <th class="cell_center">Tipo Identificaci&oacute;n</th> --}}
                                                        <th class="cell_center">Nombres</th>
                                                        <th class="cell_center">Apellidos</th>
                                                        <th class="cell_center">Correo electr&oacute;nico</th>
                                                        <th class="cell_center">Tel&eacute;fono</th>
                                                        <th class="cell_center">Tipo usuario</th>
                                                        <th class="cell_center">Estado</th>
                                                        <th class="cell_center" style="width: 10%;">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($usuarios) > 0)
                                                        @foreach($usuarios as $usuario)
                                                        <tr style="cursor: pointer;" json-data='@json($usuario)'>
                                                            <td class="edit_row">{{ $usuario->abreviacion }}: {{ $usuario->documento }}</td>
                                                            {{-- <td class="cell_center edit_row">{{ $usuario->id_tipo_identificacion }}</td> --}}
                                                            <td class="edit_row">{{ $usuario->nombres }}</td>
                                                            <td class="edit_row">{{ $usuario->apellidos }}</td>
                                                            <td class="edit_row">{{ $usuario->correo_electronico }}</td>
                                                            <td class="edit_row">{{ $usuario->telefono }}</td>
                                                            <td class="cell_center edit_row">{{ $usuario->descripcion }}</td>
                                                            <td class="cell_center edit_row">{{ $usuario->estado }}</td>
                                                            <td class="cell_center">
                                                                <button type="button" ide="{{ $usuario->id }}" class="modify_row btn btn-info"><i class="fa fa-pencil-square"></i></button>
                                                                &nbsp;&nbsp;
                                                                <button type="button" ide="{{ $usuario->id }}" class="delete_row btn btn-inverse"><i class="fa fa-trash-o"></i></button>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    {{-- @else
                                                    <tr>
                                                        <td colspan="8">No hay informaci&oacute;n para mostrar</td>
                                                    </tr> --}}
                                                    @endif
                                                </tbody>
                                            </table>
                                            {{-- <div class="pagination-blobk">
                                                {{ $pacientes->links('layouts.paginationlinks') }}
                                            </div> --}}
                                            <form id="form_delete" action="{{ route('admin.delete_usu') }}" method="post" style="display: none;">
                                                @csrf
                                                <input type="hidden" id="input_delete" name="input_delete">
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div id="div_edit_form" class="panel panel-info" style="display: none;">
                                <div class="panel-heading"><i  class="{{ $opcion->icono }}"></i>&nbsp;&nbsp;Actualizar informaci&oacute;n del usuario</div>
                                <div class="panel-wrapper collapse in" aria-expanded="true">
                                    <div class="panel-body">
                                        <form action="{{ route('admin.update_usu') }}" method="post" id="update-form">
                                            @csrf

                                            <div class="form-body">
                                                <input type="hidden" id="id_edit" name="id_edit" value="{{ old('id_edit') }}">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Nombres</label>
                                                            <input type="text" id="nombres_edit" name="nombres_edit" class="form-control" placeholder="Ingrese nombres" value="{{ old('nombres_edit') }}">
                                                            <span class="text-danger">@error('nombres_edit') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Apellidos</label>
                                                            <input type="text" id="apellidos_edit" name="apellidos_edit" class="form-control" placeholder="Ingrese apellidos" value="{{ old('apellidos_edit') }}">
                                                            <span class="text-danger">@error('apellidos_edit') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Tipo identificaci&oacute;n</label>
                                                            <select id="id_tipo_identificacion_edit" name="id_tipo_identificacion_edit" class="form-control selectpicker show-tick" title="Seleccione...">
                                                                <option value="1" {{ old('id_tipo_identificacion_edit') == "1"  || old('id_tipo_identificacion_edit') == null ? 'selected' : '' }}>C&eacute;dula</option>
                                                                <option value="2" {{ old('id_tipo_identificacion_edit') == "2" ? 'selected' : '' }}>Tarjeta Identidad</option>
                                                                <option value="3" {{ old('id_tipo_identificacion_edit') == "3" ? 'selected' : '' }}>Pasaporte</option>
                                                                <option value="4" {{ old('id_tipo_identificacion_edit') == "4" ? 'selected' : '' }}>C&eacute;dula extranjer&iacute;a</option>
                                                            </select>
                                                            <span class="text-danger">@error('id_tipo_identificacion_edit') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Identificaci&oacute;n</label>
                                                            <input type="text" id="documento_edit" name="documento_edit" readonly="" class="form-control onlyNumbers" placeholder="Ingrese identificaci&oacute;n" value="{{ old('documento_edit') }}">
                                                            <span class="text-danger">@error('documento_edit') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-10 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Correo electr&oacute;nico</label>
                                                            <input type="email" id="correo_electronico_edit" name="correo_electronico_edit" class="form-control" placeholder="Ingrese correo electr&oacute;nico" value="{{ old('correo_electronico_edit') }}">
                                                            <span class="text-danger">@error('correo_electronico_edit') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Fecha nacimiento</label>
                                                            <div style="position:relative">
                                                                <div class="input-group">
                                                                    <input type="text" id="fecha_nacimiento_edit" name="fecha_nacimiento_edit" class="form-control datepicker withadon" autocomplete="off" placeholder="yyyy-mm-dd" value="{{ old('fecha_nacimiento_edit') }}">
                                                                    <div class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-th"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <span class="text-danger">@error('fecha_nacimiento_edit') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Tel&eacute;fono</label>
                                                            <input type="text" id="telefono_edit" name="telefono_edit" class="form-control onlyNumbers" placeholder="Ingrese tel&eacute;fono" value="{{ old('telefono_edit') }}">
                                                            <span class="text-danger">@error('telefono_edit') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Direcci&oacute;n</label>
                                                            <input type="text" id="direccion_edit" name="direccion_edit" class="form-control" placeholder="Ingrese direcci&oacute;n" value="{{ old('direccion_edit') }}">
                                                            <span class="text-danger">@error('direccion_edit') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="password_edit" class="control-label">Contrase&ntilde;a:</label>
                                                            <div class="input-group">
                                                                <input type="password" class="form-control withadon" id="password_edit" name="password_edit" value="{{ old('password_edit') }}">
                                                                <div class="input-group-addon"><i class="ti-lock"></i></div>
                                                            </div>
                                                            <span class="text-danger">@error('password_edit') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="password_edit_confirmation" class="control-label">Repita la contrase&ntilde;a:</label>
                                                            <div class="input-group">
                                                                <input type="password" class="form-control withadon" id="password_edit_confirmation" name="password_edit_confirmation" value="{{ old('password_edit_confirmation') }}">
                                                                <div class="input-group-addon"><i class="ti-lock"></i></div>
                                                            </div>
                                                            <span class="text-danger">@error('password_edit_confirmation') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Tipo usuario</label>
                                                            <select id="id_tipo_usuario_edit" name="id_tipo_usuario_edit" class="form-control selectpicker show-tick dropdown" data-dropup-auto="false" data-live-search="true" data-size="3" title="Seleccione...">
                                                                @if(count($tipos_usuarios) > 0)
                                                                    @foreach($tipos_usuarios as $tipo_usuario)
                                                                        <option value="{{ $tipo_usuario->id }}" {{ old('id_tipo_usuario_edit') == $tipo_usuario->id ? 'selected' : '' }}>{{ $tipo_usuario->descripcion }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <span class="text-danger">@error('id_tipo_usuario_edit') {{ $message }} @enderror</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="control-label">Estado</label>
                                                            <input id="estado_edit" name="estado_edit" type="hidden" value="">
                                                            <div id="div_estado_tmp_edit" class="checkbox checkbox-danger">
                                                                <input id="estado_tmp_edit" name="estado_tmp_edit" type="checkbox">
                                                                <label id="label_estado_tmp_edit" for="estado_tmp_edit"> Inactivo </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions m-t-20">
                                                <button type="submit" class="btn btn-info"> <i class="fa fa-save"></i> Actualizar informaci&oacute;n del usuario</button>
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
