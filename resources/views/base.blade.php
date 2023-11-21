<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Módulo de Prevención y Control de la Función Pública de la personería de Bogotá DC.">
    <meta name="author" content="FerManjarrés">
    <link href="{{asset('favicon.ico')}}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <title>@yield('Xtitle')</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/fontawesome-free/css/all.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Estilos Noty -->
    <link href="{{asset('vendor/noty/noty.css')}}" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">

    <!-- Custom styles for base-->
    <link href="{{asset('css/base.css')}}" rel="stylesheet">

    <!-- Estilos propios de esta página-->
    @yield('Xestilos')
</head>

<body id="page-top">

    <!-- Spinner Loading Page -->
    <div id="loading">
        <div class="lds-facebook">
            <div style="border: solid 1px;"></div>
            <div style="border: solid 1px;"></div>
            <div style="border: solid 1px;"></div>
        </div>
    </div>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @yield('Xsidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @yield('Xtopbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    @yield('Xhead')

                    @yield('Xcontent')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @yield('Xfooter')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Modal Confirmación -->
    <div class="modal fade" id="confirmacionModal" tabindex="-1" role="dialog" aria-labelledby="confirmacionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
            <div class="modal-header" style="padding: 5px 10px 5px 10px;">
                <i class="fas fa-comment-dots" style="font-size: 24px; margin-right: 10px;"></i> Confirmación
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 5px 10px 5px 10px;">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 text-center" id="confirmacionMsj">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 5px 10px 5px 10px; margin: 0 auto;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="padding: 2px 5px 2px 5px;">Cancelar</button>
                <button id="confirmacionBtn" type="button" class="btn btn-primary" data-dismiss="modal" style="padding: 2px 5px 2px 5px;">Aceptar</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Core noty JavaScript-->
    <script src="{{asset('vendor/noty/noty.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.js')}}"></script>

    <!-- Custom scripts base-->
    <script src="{{asset('js/base.js')}}"></script>
    <script src="{{asset('js/plantillas.js')}}"></script>

    <script type="text/javascript">
        var baseTrabajo = {
            id_perfil: {{$sesion->trabajo->id_perfil}},
            firma: {!!(is_null($sesion->firma))?"false":"true";!!}
        };
        var festivos = ['{!!implode("', '",$sesion->festivos)!!}'];
        $('#li_{{$slag}}').addClass('active');
        $('#li_{{$slag}}').find('.nav-link').removeClass('collapsed');
        $('#colps{{$slag}}').addClass('show');
        $('a[href="'+window.location.pathname+'"]').addClass('active');
    </script>

    <!-- Custom scripts for a page-->
@yield('Xscripts')

</body>

</html>
