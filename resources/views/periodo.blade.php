@extends('menus')
@section('content')
    <style>
        .isDisabled2 {
            color: currentColor;
            cursor: not-allowed;
            opacity: 0.5;
            text-decoration: none;
            pointer-events: none;
        }
    </style>
    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <br>
            <div class="card-header ">
                <h4>
                    Periodo-{{Session('gest-nombre','si no hay variable')}}
                    <button type="button" class="btn btn-primary fa fa-plus-circle" data-toggle="modal" data-target="#new-periodo" data-toggle="tooltip" title="Agregar" id="nuevo">
                    </button>
                    <button type="button" id="print" name="print" class="btn btn-primary btn-info fa fa-print" data-toggle="tooltip" title="Imprimir">
                    </button>
                </h4>
                <input value="{{Auth::user()->id}}" id="user" name="user" type="hidden">
                <input value="{{Session('emp-id')}}" id="empresa" name="empresa" type="hidden">
                <input value="{{Session('gest-id')}}" id="gestion" name="gestion" type="hidden">

                @forelse($periodos as $periodo)
                    <input id="estado_com" name="estado_com" type="hidden" value="{{$periodo->Nombres}}">

                @empty

                @endforelse
                <script>
                    $(document).ready(function(){
                        $('#print').click(function () {
                            var usuario = $('#user').val();
                            // var empresa = $('#empresa').val();
                            var gestion = $('#gestion').val();
                            javascript:window.open('http://localhost:8000/Reportes/periodo.php?gestion='+gestion+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                        })
                    });
                </script>
            </div>
            <div class="card-body table-full-width table-responsive">
                @if(Session::has('danger-periodo'))
                    <script>
                        $(function() {
                            $('#error').modal('show');
                        });
                    </script>
                    <div id="error" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                        <div  style="margin-left: 76%;margin-right: 1%;" class="modal-dialog modal-sm " role="document">
                            <div class="modal-content alert-danger">
                                <div class="col-md-10 pl-1">
                                    <p style="color: #FFFFFF">No se pudo eliminar <b style="color: #FFFFFF">{!! session('danger-periodo') !!}</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(Session::has('alert-periodo'))
                    <script>
                        $(function() {
                            $('#alert').modal('show');
                        });
                    </script>
                    <div id="alert" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                        <div  style="margin-left: 76%;margin-right: 1%;" class="modal-dialog modal-sm " role="document">
                            <div class="modal-content alert-warning">
                                <div class="col-md-10 pl-1">
                                    <p style="color: #3c76c7"><b>{!! session('alert-periodo') !!}</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <table class="table table-hover table-striped">
                    <thead>
                    <th>Nombre</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Estado</th>
                    <th></th>
                    </thead>
                    <tbody>
                    @forelse($periodos as $periodo)
                        <tr class="data-row">
                            <td class="nombre">{{$periodo->Nombre}}</td>
                            <td class="inicio">{{date("d/m/Y",strtotime($periodo->FechaInicio))}}</td>
                            <td class="fin">{{date("d/m/Y",strtotime($periodo->FechaFin))}}</td>
                            @foreach ($conceptos as $concepto)
                                @if ($periodo->Estado== $concepto->id)
                                    <td>{{$concepto->Nombres}}</td>
                                @endif
                            @endforeach
                            <td>
                                <button style="color: rgb(255,255,255)" class="btn btn-primary btn-fill fa fa-pencil isDisabled{{$periodo->Estado}}" name="edit-item" id="edit-item" data-id="{{$periodo->id}}" data-inicio="{{$periodo->FechaInicio}}" data-fin="{{$periodo->FechaFin}}" title="Editar"></button>
                                <a class="btn btn-danger btn-fill fa fa-trash-o isDisabled{{$periodo->Estado}}" id="btn_eliminar" name="btn_eliminar" data-toggle="tooltip" title="Eliminar" href="periodo/{{$periodo->id}}/delete" data-confirm="¿Estas seguro que quieres borrar {{$periodo->Nombre}}?"></a>
                            </td>

                        </tr>
                    @empty
                        <div class="alert alert-danger" role="alert">No existen Periodos</div>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('model')
    <!-- *************Codigo error********************************** -->
    @if(!empty(Session::get('error_code')) && Session::get('error_code') == 5)
        <script>
            $(function() {
                $('#new-periodo').modal('show');
            });
        </script>
    @endif
    @if(!empty(Session::get('error_code')) && Session::get('error_code') == 4)
        <script>
            $(function() {
                $('#edit-periodo').modal('show');
            });
        </script>
    @endif
    <!-- *************Modal Eliminar******************************** -->
    <script>
        $(document).ready(function() {
            $('a[data-confirm]').click(function(ev) {
                var href = $(this).attr('href');
                if (!$('#dataConfirmModal').length) {
                    $('body').append('<div class="modal fade in" id="dataConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: block;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cancelar</button><a style="color: #ffffff" class="btn btn-primary btn-fill" id="dataConfirmOK">Aceptar</a></div></div></div></div>');
                }
                $('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
                $('#dataConfirmOK').attr('href', href);
                $('#dataConfirmModal').modal({show:true});
                return false;
            });
        });
    </script>
    <!-- *************Modal Edit******************************** -->
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script>



        $(document).ready(function() {

            $(document).on('click', "#edit-item", function() {
                $(this).addClass('edit-item-trigger-clicked'); //useful for identifying which trigger was clicked and consequently grab data from the correct row and not the wrong one.

                var options = {
                    'backdrop': 'static'
                };
                $('#edit-periodo').modal(options)
            })

            // on modal show
            $('#edit-periodo').on('show.bs.modal', function() {
                var el = $(".edit-item-trigger-clicked"); // See how its usefull right here?
                var row = el.closest(".data-row");

                // get the data
                var id = el.data('id');
                var inicio = el.data('inicio');
                var fin = el.data('fin');
                var nombre = row.children(".nombre").text();
                // var inicio = row.children(".inicio").text();
                // var fin = row.children(".fin").text();

                // fill the data in the input fields
                //$("#razon_social").val(id);
                $("#pk-periodo").val(id);
                $("#nombres").val(nombre);
                $("#fechas_inicio").val(inicio);
                $("#fechas_fin").val(fin);

            })

            // on modal hide
            $('#edit-periodo').on('hide.bs.modal', function() {
                $('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked')
                $("#edit-form").trigger("reset");
            });
        });

        $("#cerrar").click(function(event) {
            $("#form-new")[0].reset();
        });

    </script>



    <!-- Modal Periodo new -->
    <div class="modal fade col-lg-12" id="new-periodo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" style="height: 50px;" role="document">
            <div class="modal-content card-body" >
                <div>
                    <h4 class="modal-title">Nuevo Periodo</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form" method="post" action="{{url('periodo/create')}}" id="form-new">
                        {!! csrf_field() !!}
                        <div class="panel-body">

                            <input type="hidden" name="gestion_id" id="gestion_id" value="{{Session('gest-id')}}">

                            <div class="row">
                                <div id="nombre" class="form-group col-md-12 pl-1" {{ $errors->has('nombre') ? ' has-error' : '' }}>
                                    <label for="nombre" class="control-label">Razon Social:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" maxlength="15" value="{{ old('nombre') }}" required>
                                    @if ($errors->has('nombre'))
                                        <span id="alerta"  class="help-block">
                                        <strong class="text-danger">{{ $errors->first('nombre') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 pl-1" {{ $errors->has('fecha_inicio') ? ' has-error' : '' }}>
                                    <label for="fecha_inicio" class="control-label">Fecha inicio:</label>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                                    @if ($errors->has('fecha_inicio'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('fecha_inicio') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group col-md-6 pl-1" {{ $errors->has('fecha_fin') ? ' has-error' : '' }}>
                                    <label for="fecha_fin" class="control-label">Fecha fin:</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                                    @if ($errors->has('fecha_fin'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('fecha_fin') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">

                            <button id="cerrar" type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary btn-fill">Guardar</button>
                        </div>

                        <script>
                            $("#nuevo").click(function(event) {
                                $("#form-new")[0].reset();
                            });
                        </script>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- Modal Periodo editar -->
    <div class="modal fade col-lg-12" id="edit-periodo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" style="height: 50px;" role="document">
            <div class="modal-content card-body" >
                <div>
                    <h4 class="modal-title">Editar Periodo</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form" method="post" action="{{url('periodo/update')}}" id="form-new">
                        {!! csrf_field() !!}
                        <div class="panel-body">

                            <input type="hidden" name="pk-periodo" id="pk-periodo" value="{{ old('pk-periodo') }}">

                            <div class="row">
                                <div class="form-group col-md-12 pl-1" {{ $errors->has('nombres') ? ' has-error' : '' }}>
                                    <label for="nombres" class="control-label">Razon Social:</label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" maxlength="15" value="{{ old('nombres') }}" required>
                                    @if ($errors->has('nombres'))
                                        <span id="alerta"  class="help-block">
                                        <strong class="text-danger">{{ $errors->first('nombres') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 pl-1" {{ $errors->has('fechas_inicio') ? ' has-error' : '' }}>
                                    <label for="fechas_inicio" class="control-label">Fecha inicio:</label>
                                    <input type="date" class="form-control" id="fechas_inicio" name="fechas_inicio" value="{{ old('fechas_inicio') }}" required>
                                    @if ($errors->has('fechas_inicio'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('fechas_inicio') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group col-md-6 pl-1" {{ $errors->has('fechas_fin') ? ' has-error' : '' }}>
                                    <label for="fechas_fin" class="control-label">Fecha fin:</label>
                                    <input type="date" class="form-control" id="fechas_fin" name="fechas_fin" value="{{ old('fechas_fin') }}" required>
                                    @if ($errors->has('fechas_fin'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('fechas_fin') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary btn-fill">Guardar</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <script>
        $(document).ready(function () {
            var estado_com = document.getElementById("estado_com").value;

            if (estado_com == "Cerrado") {
                $("#edit-item").prop("disabled", true);
                $("#btn_eliminar").prop("disabled", true);
            }
        });
    </script>

@endsection