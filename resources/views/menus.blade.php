<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Empresa</title>
    <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="images/favicon.png" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <!-- plugin css for this page -->
    <link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/font-awesome.min.css" />

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    {{--<script src="js/bootstrap.min.js"></script>--}}
    {{--<script src="js/jquery.min.js"></script>--}}


    <script src="jstree/jstree.min.js"></script>
    <link rel="stylesheet" href="jstree/themes/default/style.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">


</head>
<body>

<div id="app" >
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/empresa') }}">
                {{--{{ config('app.name', 'Empresa') }}--}}
                Empresa:
                {{Session('emp-Nombre','si no hay variable')}}

                {{--{{Session('emp-Nivel','si no hay variable')}}--}}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <!-- partial:../../partials/_sidebar.html -->

            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <div class="nav-link">

                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="empresa">
                            <i class="menu-icon mdi mdi-home"></i>
                            <span class="menu-title">Empresa</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="gestion">
                            <i class="menu-icon mdi mdi-book-open"></i>
                            <span class="menu-title">Gestion</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="cuenta">
                            <i class="menu-icon mdi mdi-chart-line"></i>
                            <span class="menu-title">Cuenta</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="comprobante">
                            <i class="menu-icon mdi mdi-clipboard-text"></i>
                            <span class="menu-title">Comprobante</span>
                        </a>
                    </li>



                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                            <i class="menu-icon mdi mdi-content-copy"></i>
                            <span class="menu-title">Reportes</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="librodiario">Libro Diario</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="libromayor">Libro Mayor</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="balanceinicial">Balance Inicial</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="balancegeneral">Balance General</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="resultado">Resultado</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="sumsaldo">Sumas y Saldos</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#inventario" aria-expanded="false" aria-controls="inventario">
                            <i class="menu-icon mdi mdi-forklift"></i>
                            <span class="menu-title">Inventario</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="inventario">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="categoria">Categorias</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="articulos">Articulos</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#notas" aria-expanded="false" aria-controls="notas">
                            <i class="menu-icon mdi mdi-note-multiple-outline"></i>
                            <span class="menu-title">Notas</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="notas">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="venta">Venta</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="compra">Compra</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#configuracion" aria-expanded="false" aria-controls="configuracion">
                            <i class="menu-icon mdi mdi-settings"></i>
                            <span class="menu-title">Configuración</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="configuracion">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="moneda"> Moneda </a>
                                </li>
                            </ul>
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="cuentaintegracion"> Integracion </a>
                                </li>
                            </ul>
                        </div>
                    </li>



                </ul>
            </nav>

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">

                    <div class="card">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @yield('model')

</div>


<script src="vendors/js/vendor.bundle.base.js"></script>
<script src="vendors/js/vendor.bundle.addons.js"></script>
<script src="js/off-canvas.js"></script>
<script src="js/misc.js"></script>
<script src="js/dashboard.js"></script>
{{--<script src="public/bootstrap/fonts/materialdesignicons-webfont.woff2"></script>--}}
<!--   Core JS Files   -->
{{--<script src="{{ asset('https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js') }}" type="text/javascript"></script>--}}
{{--<script src="{{ asset('assets/js/core/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="{{ asset('assets/js/plugins/bootstrap-switch.js') }}"></script>
<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<!--  Chartist Plugin  -->
<script src="{{ asset('assets/js/plugins/chartist.min.js') }}"></script>
<!--  Notifications Plugin    -->
<script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>
<!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
<script src="{{ asset('assets/js/light-bootstrap-dashboard.js?v=2.0.1') }}" type="text/javascript"></script>
<!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->--}}
</body>

</html>