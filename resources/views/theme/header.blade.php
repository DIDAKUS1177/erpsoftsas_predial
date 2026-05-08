<div class="navbar-header">
    <div class="top-left-part">
        <!-- Logo -->
        <a class="logo" href="/profile">
            <!-- Logo icon image, you can use font-icon also -->
            <b>
            <!--This is dark logo icon-->
            <img src="{!! asset('theme/plugins/images/admin-logo.png') !!}" alt="home" class="dark-logo" />
            <!--This is light logo icon-->
            <img src="{!! asset('theme/plugins/images/admin-logo-dark.png') !!}" alt="home" class="light-logo" />
            </b>
            <!-- Logo text image you can use text also -->
            <span class="hidden-xs">
                <!--This is dark logo text-->
                <img src="{!! asset('theme/plugins/images/' . Session::get('admin_text_dark')) !!}" alt="home" class="dark-logo" />
                <!--This is light logo text-->
                <img src="{!! asset('theme/plugins/images/' . Session::get('admin_text')) !!}" alt="home" class="light-logo" />
            </span>
        </a>
    </div>
    <ul id="buttonsTareas" class="nav navbar-top-links navbar-left">
        <li>
            <a href="javascript:void(0)" class="open-close waves-effect waves-light visible-xs"><i class="ti-close ti-menu"></i></a>
        </li>
        @yield('buttons')
    </ul>
    <ul class="nav navbar-top-links navbar-right pull-right">
        <li class="dropdown">
            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#">
                <img src="{!! asset('theme/plugins/images/users/agent2.png') !!}" alt="user-img" width="30" class="img-circle">
                <b class="hidden-xs">{{ Session::get('username') }}</b><span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-user animated flipInY">
                <li>
                    <div class="dw-user-box">
                        <div class="u-img">
                            <img src="{!! asset('theme/plugins/images/users/agent2.png') !!}" alt="user" />
                        </div>
                        <div class="u-text">
                            <h4>{{ Session::get('username') }} {{ Session::get('userlastname') }}</h4>
                            <p class="text-muted">{{ Session::get('useremail') }}</p>
                        </div>
                    </div>
                </li>
                <li role="separator" class="divider"></li>
                <li><a href="{{ route('auth.index_change_pass') }}"><i class="ti-unlock"></i> Cambiar contrase&ntilde;a</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="{{ route('auth.logout') }}"><i class="fa fa-power-off"></i> Cerrar sesi&oacute;n</a></li>
            </ul>
        </li>
    </ul>
</div>
