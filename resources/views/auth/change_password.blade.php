@extends('theme.default')
@section('content')

@push('scripts')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\PasswordChangeFormRequest', '#change-pass-form'); !!}
@endpush
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-5 col-md-8 col-sm-12 col-xs-12">
            <h4 class="page-title">Cambio de contrase&ntilde;a</h4>
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
        <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <form method="post" action="{{ route('auth.change_pass') }}" id="change-pass-form">
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
                            <div class="form-group">
                                <label for="current_password" class="control-label">Contrase&ntilde;a actual:</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="current_password" name="current_password" value="{{ old('current_password') }}">
                                    <div class="input-group-addon"><i class="ti-lock"></i></div>
                                </div>
                                <span class="text-danger">@error('current_password') {{ $message }} @enderror</span>
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label">Nueva contrase&ntilde;a:</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
                                    <div class="input-group-addon"><i class="ti-lock"></i></div>
                                </div>
                                <span class="text-danger">@error('password') {{ $message }} @enderror</span>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="control-label">Repita la nueva contrase&ntilde;a:</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}">
                                    <div class="input-group-addon"><i class="ti-lock"></i></div>
                                </div>
                                <span class="text-danger">@error('password_confirmation') {{ $message }} @enderror</span>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Guardar</button>
                                <!--<button type="submit" class="btn btn-inverse waves-effect waves-light">Cancelar</button>-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
            <div class="white-box">
                Se recomienda:
                <ul>
                    <li>M&iacute;nimo 6 caract&eacute;res</li>
                    <li>Al menos una letra may&uacute;scula</li>
                    <li>Al menos un n&uacute;mero</li>
                    <li>Al menos un simbolo ~ ! @ - # $</li>
                </ul>
                <br />
                <button id="btn_generar_password" type="button" class="btn btn-info">Generar password aleatoriamente</button>
                <div id="div_random_text" style="font-size: 20pt; font-weight: bold; padding-top: 10px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection
