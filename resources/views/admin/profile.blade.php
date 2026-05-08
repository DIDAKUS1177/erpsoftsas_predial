@extends('theme.default')
@section('content')
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">{{ Session::get('desc_role') }}</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h1 style="color: #c0c0c0;">{{ Session::get('desc_role') }}</h1>
            </div>
        </div>
    </div>
</div>
@endsection
