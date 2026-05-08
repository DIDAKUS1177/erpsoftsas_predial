<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="refresh" content="{{ intval(config('session.lifetime')) * 60 }};url=/">
    <link rel="icon" type="image/png" sizes="16x16" href="{!! asset('theme/plugins/images/favicon.png') !!}">
    <title>Plataforma - ERPSoft Predial</title>
    <!-- Bootstrap Core CSS -->
    <link href="{!! asset('theme/bootstrap/dist/css/bootstrap.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('theme/plugins/bower_components/datatables/jquery.dataTables.min.css') !!}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/select/1.3.4/css/select.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/select/1.3.4/css/select.dataTables.min.css" rel="stylesheet" type="text/css" />

    <!-- Menu CSS -->
    <link href="{!! asset('theme/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') !!}" rel="stylesheet">
    <!-- toast CSS -->
    <link href="{!! asset('theme/plugins/bower_components/toast-master/css/jquery.toast.css') !!}" rel="stylesheet">
    <!-- morris CSS -->
    <link href="{!! asset('theme/plugins/bower_components/morrisjs/morris.css') !!}" rel="stylesheet">
    <!-- chartist CSS -->
    <link href="{!! asset('theme/plugins/bower_components/chartist-js/dist/chartist.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('theme/plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css') !!}" rel="stylesheet">
    {{-- <link href="{!! asset('theme/bootstrap/datepicker/css/bootstrap-datepicker3.standalone.min.css') !!}" rel="stylesheet" /> --}}
    <link href="{!! asset('theme/bootstrap/jqdatepicker/datepicker.min.css') !!}" rel="stylesheet" />
    <link href="{!! asset('theme/plugins/bower_components/clockpicker/dist/bootstrap-clockpicker.min.css') !!}" rel="stylesheet" />
    <link href="{!! asset('theme/plugins/bower_components/bootstrap-select/bootstrap-select.min.css') !!}" rel="stylesheet" />
    <!--select2 CSS -->
    <link href="{!! asset('theme/plugins/bower_components/select2/css/select2.min.css') !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('theme/plugins/bower_components/select2/css/select2-options.css') !!}" rel="stylesheet" type="text/css">
    <!--alerts CSS -->
    <link href="{!! asset('theme/plugins/bower_components/sweetalert/sweetalert.css') !!}" rel="stylesheet" type="text/css">
    <!-- animation CSS -->
    <link href="{!! asset('theme/css/animate.css') !!}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{!! asset('theme/css/style.css') !!}" rel="stylesheet">
    <!-- color CSS -->
    <link href="{!! asset('theme/css/colors/green.css') !!}" id="theme" rel="stylesheet">
    <!-- Timeline CSS -->
    <link href="{!! asset('theme/plugins/bower_components/horizontal-timeline/css/horizontal-timeline.css') !!}" rel="stylesheet">
    <link href="{!! asset('theme/plugins/bower_components/switchery/dist/switchery.min.css') !!}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="{!! asset('theme/css/customcss/style.css') !!}" rel="stylesheet">

    <!-- Calendar CSS -->
    {{-- <link href="{!! asset('theme/plugins/bower_components/calendar/dist/fullcalendar.css') !!}" rel="stylesheet" /> --}}
    <link href="{!! asset('theme/plugins/bower_components/fullcalendar3.9/fullcalendar.min.css') !!}" rel="stylesheet" />

    <link href="{!! asset('theme/css/jquery.powertip.min.css') !!}" rel="stylesheet" />

</head>

<body class="fix-header">
    <!-- ============================================================== -->
    <!-- Preloader -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Wrapper -->
    <!-- ============================================================== -->
    <div id="wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            @include('theme.header')
            {{-- @include('theme.header', ['template' => $template]) --}}
        </nav>
        <!-- End Top Navigation -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <div class="navbar-default sidebar" role="navigation">
            @include('theme.sidebar')
        </div>
        <!-- ============================================================== -->
        <!-- End Left Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page Content -->
        <!-- ============================================================== -->
        <div id="page-wrapper">
            @yield('content')
            @yield('modales')
            @yield('resolucion')
            <footer class="footer text-center">
                <script>document.write(new Date().getFullYear())</script>
                &copy; ERPSoft Predial - Software Development Team
            </footer>
        </div>
        <!-- ============================================================== -->
        <!-- End Page Content -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{!! asset('theme/plugins/bower_components/jquery/dist/jquery.min.js') !!}"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{!! asset('theme/bootstrap/dist/js/bootstrap.min.js') !!}"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="{!! asset('theme/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') !!}"></script>
    <!--slimscroll JavaScript -->
    <script src="{!! asset('theme/js/jquery.slimscroll.js') !!}"></script>
    <!--Wave Effects -->
    <script src="{!! asset('theme/js/waves.js') !!}"></script>
    <!--Counter js -->
    <script src="{!! asset('theme/plugins/bower_components/waypoints/lib/jquery.waypoints.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/counterup/jquery.counterup.min.js') !!}"></script>
    <!--Morris JavaScript -->
    <script src="{!! asset('theme/plugins/bower_components/raphael/raphael-min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/morrisjs/morris.js') !!}"></script>
    <!-- chartist chart -->
    <script src="{!! asset('theme/plugins/bower_components/chartist-js/dist/chartist.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js') !!}"></script>
    <!-- Calendar JavaScript -->
    <script src="{!! asset('theme/plugins/bower_components/moment/moment.js') !!}"></script>

    {{-- <script src="{!! asset('theme/bootstrap/datepicker/js/bootstrap-datepicker.min.js') !!}"></script> --}}
    {{-- <script src="{!! asset('theme/bootstrap/datepicker/locales/bootstrap-datepicker.es.min.js') !!}"></script> --}}

    <script src="{!! asset('theme/bootstrap/jqdatepicker/datepicker.min.js') !!}"></script>
    <script src="{!! asset('theme/bootstrap/jqdatepicker/datepicker.es-ES.js') !!}"></script>

    <script src="{!! asset('theme/plugins/bower_components/clockpicker/dist/bootstrap-clockpicker.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/bootstrap-select/bootstrap-select.min.js') !!}"></script>

    {{-- <script src="{!! asset('theme/plugins/bower_components/calendar/dist/lang/es.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/calendar/dist/fullcalendar.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/calendar/dist/cal-init.js') !!}"></script> --}}

    <script src="{!! asset('theme/plugins/bower_components/fullcalendar3.9/lib/moment.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/fullcalendar3.9/fullcalendar.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/fullcalendar3.9/locale/es.js') !!}"></script>

    <!-- DataTables JavaScript -->
    <script src="{!! asset('theme/plugins/bower_components/datatables/jquery.dataTables.min.js') !!}"></script>

    <!-- Select2 JavaScript -->
    <script src="{!! asset('theme/plugins/bower_components/select2/js/select2.full.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/select2/js/i18n/es.js') !!}"></script>

    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.18/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.4/js/select.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.4/js/select.dataTables.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{!! asset('theme/js/custom.min.js') !!}"></script>
    {{-- <script src="{!! asset('theme/js/dashboard1.js') !!}"></script> --}}
    <!-- Custom tab JavaScript -->
    <script src="{!! asset('theme/js/cbpFWTabs.js') !!}"></script>
    <script type="text/javascript">
        (function () {
                [].slice.call(document.querySelectorAll('.sttabs')).forEach(function (el) {
                new CBPFWTabs(el);
            });
        })();
    </script>
    <script src="{!! asset('theme/plugins/bower_components/toast-master/js/jquery.toast.js') !!}"></script>
    <!--Style Switcher -->
    <script src="{!! asset('theme/plugins/bower_components/styleswitcher/jQuery.style.switcher.js') !!}"></script>
    <!-- Sweet-Alert  -->
    <script src="{!! asset('theme/plugins/bower_components/sweetalert/sweetalert.min.js') !!}"></script>
    <!-- Horizontal-timeline JavaScript -->
    <script src="{!! asset('theme/plugins/bower_components/horizontal-timeline/js/horizontal-timeline.js') !!}"></script>

    <script src="{!! asset('theme/js/jquery.powertip.min.js') !!}" type="text/javascript" ></script>
    <script src="{!! asset('theme/plugins/bower_components/switchery/dist/switchery.min.js') !!}"></script>
    <script src="{!! asset('theme/plugins/bower_components/styleswitcher/jQuery.style.switcher.js') !!}"></script>
    <script src="{!! asset('theme/js/customjs/site.js') !!}"></script>
    @stack('scripts')
</body>

</html>
