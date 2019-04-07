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
                <h4 >
                    Gestion
                    {{--Gestion-{{Session('emp-nombre','si no hay variable')}}--}}
                    <button type="button" class="btn btn-primary fa fa-plus-circle" data-toggle="modal" data-target="#new-gestion" data-toggle="tooltip" title="Agregar" id="nuevo">
                    </button>
                    <button id="print" name="print" type="button" class="btn btn-primary btn-info fa fa-print" data-toggle="tooltip" title="Imprimir" >
                    </button>
                </h4>

                <input value="{{Auth::user()->id}}" id="user" name="user" type="hidden">
                <input value="{{Session('emp-id')}}" id="empresa" name="empresa" type="hidden">
                {{--<input value="{{Session('gest-id')}}" id="gestion" name="gestion" type="text">--}}


                @forelse($gestiones as $gestion)
                    <input id="estado_com" name="estado_com" type="hidden" value="{{$gestion->Nombres}}">

                @empty

                @endforelse

                <script>
                    // $(document).ready(function () {
                    //     var estado_com = document.getElementById("estado_com").value;
                    //
                    //     if (estado_com == "Cerrado") {
                    //         $("#edit-item").prop("disabled", true);
                    //         $("#btn-eliminar").prop("disabled", true);
                    //     }
                    // });
                </script>
                <script>
                    $(document).ready(function(){


                        $('#print').click(function () {
                            var usuario = $('#user').val();
                            var empresa = $('#empresa').val();
                            // var gestion = $('#gestion').val();
                            javascript:window.open('http://localhost:8000/Reportes/gestion.php?empresa='+empresa+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                        })
                    });
                </script>
            </div>
            <div class="card-body table-full-width table-responsive">
                @if(Session::has('danger-gestion'))
                    <script>
                        $(function() {
                            $('#error').modal('show');
                        });
                    </script>
                    <div id="error" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                        <div style="margin-left: 76%;margin-right: 1%;" class="modal-dialog modal-sm " role="document">
                            <div class="modal-content alert-danger">
                                <div class="col-md-10 pl-1">
                                    <p2 style="color: #ff5963">No se pudo eliminar <b style="color: #ff5963">{!! session('danger-gestion') !!}</b> tiene periodos incluidos</p2>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(Session::has('alert-gestion'))
                    <script>
                        $(function() {
                            $('#alert').modal('show');
                        });
                    </script>
                    <div id="alert" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                        <div style="margin-left: 76%;margin-right: 1%;" class="modal-dialog modal-sm " role="document">
                            <div class="modal-content alert-warning">
                                <div class="col-md-10 pl-1">
                                    <p style="color: #ff5963"><b>{!! session('alert-gestion') !!}</b></p>
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
                    @forelse($gestiones as $gestion)
                        <tr class="data-row">
                            <td class="nombre">{{$gestion->Nombre}}</td>
                            <td class="inicio">{{date("d/m/Y",strtotime($gestion->FechaInicio))}}</td>
                            <td class="fin">{{date("d/m/Y",strtotime($gestion->FechaFin))}}</td>
                            <td class="est">
                                @foreach ($conceptos as $concepto)
                                    @if ($gestion->Estado== $concepto->id)
                                        {{$concepto->Nombres}}
                                    @endif
                                @endforeach
                            </td>
                            <td>

                                <form action="{{url('redireccion')}}">
                                    <input id="id" name="id" type="hidden" value="{{$gestion->id}}">
                                    <input id="gestion" name="gestion" type="hidden" value="{{$gestion->id}}">
                                    <input id="nom" name="nom" type="hidden" value="{{$gestion->Nombre}}">
                                    <input id="inicio" name="inicio" type="hidden" value="{{$gestion->FechaInicio}}">
                                    <input id="fin" name="fin" type="hidden" value="{{$gestion->FechaFin}}">
                                    <a class="btn btn-danger btn-fill fa fa-trash-o isDisabled{{$gestion->Estado}}" data-toggle="tooltip" id="btn-eliminar" title="Eliminar" href="gestion/{{$gestion->id}}/delete" data-confirm="�Estas seguro que quieres borrar la {{$gestion->Nombre}}?"></a>
                                    <a  style="color: rgb(255,255,255)" class="btn btn-primary btn-fill fa fa-pencil isDisabled{{$gestion->Estado}}" id="edit-item" data-id="{{$gestion->id}}" data-inicio="{{$gestion->FechaInicio}}" data-fin="{{$gestion->FechaFin}}" title="Editar"></a>
                                    <a class="btn btn-danger btn-fill fa fa-close isDisabled{{$gestion->Estado}}" data-toggle="tooltip"  title="Cerrar" href="gestion/{{$gestion->id}}/cerrar" data-confirm="�Estas seguro que quiere cerrar la gestion {{$gestion->Nombre}}?"></a>
                                    <button type="submit" class="btn btn-success fa fa-check" title="Redirigir"></button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <div class="alert alert-danger" role="alert">No existen Gestiones</div>
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
                $('#new-gestion').modal('show');
            });
        </script>
    @endif
    @if(!empty(Session::get('error_code')) && Session::get('error_code') == 4)
        <script>
            $(function() {
                $('#edit-gestion').modal('show');
            });
        </script>
    @endif
    <!-- *************Modal Eliminar******************************** -->
    <script>
        $(document).ready(function() {
            function Habilitado(estado)
            {
                debugger;
                if (estado==2)
                    return "disabled";
                else
                    return "enabled";
            }
            $('a[data-confirm]').click(function(ev) {
                var href = $(this).attr('href');
                if (!$('#dataConfirmModal').length) {
                    $('body').append('<div class="modal fade in" id="dataConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: block;">' +
                        '<div class="modal-dialog"><div class="modal-content">' +
                        '<div class="modal-header"></div><div class="modal-body"></div>' +
                        '<div class="modal-footer">' +
                        '<button type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cancelar</button>' +
                        '<a style="color: #ffffff" class="btn btn-primary btn-fill" id="dataConfirmOK">Aceptar</a></div></div></div></div>');
                }
                $('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
                $('#dataConfirmOK').attr('href', href);
                $('#dataConfirmModal').modal({show:true});
                return false;
            });
        });
    </script>
    <!-- *************Modal Edit******************************** -->
    <script>
        $(document).ready(function() {

            $(document).on('click', "#edit-item", function() {
                $(this).addClass('edit-item-trigger-clicked'); //useful for identifying which trigger was clicked and consequently grab data from the correct row and not the wrong one.

                var options = {
                    'backdrop': 'static'
                };
                $('#edit-gestion').modal(options)
            })

            // on modal show
            $('#edit-gestion').on('show.bs.modal', function() {
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
                $("#pk-gestion").val(id);
                $("#nombres").val(nombre);
                $("#fecha_inicio").val(inicio);
                $("#fecha_fin").val(fin);

            })

            // on modal hide
            $('#edit-gestion').on('hide.bs.modal', function() {
                $('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked')
                $("#edit-form").trigger("reset");
            });
        });
    </script>

    <!-- Modal Gestion new -->
    <div class="modal fade col-lg-12" id="new-gestion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" style="height: 50px;" role="document">
            <div class="modal-content card-body" >
                <div>
                    <h4 class="modal-title">Nueva Gestion</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form" method="post" action="{{url('gestion/create')}}" id="form-new">
                        {!! csrf_field() !!}
                        <div class="panel-body">

                            <input type="hidden" name="empresa_id" id="empresa_id" value="{{Session('emp-id')}}">

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
    <!-- Modal Gestion Edit -->
    <div class="modal fade col-lg-12" id="edit-gestion" tabindex="-1" role="dialog" >
        <div class="modal-dialog" style="height: 50px;" role="document">
            <div class="modal-content card-body" >
                <div>
                    <h4 class="modal-title">Editar Gestion</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form" method="post" action="{{url('gestion/update')}}" id="form-new">
                        {!! csrf_field() !!}
                        <div class="panel-body">

                            <input type="hidden" name="pk-gestion" id="pk-gestion" value="{{old('pk-gestion')}}" >

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
                            <button type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary btn-fill">Guardar</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

@endsection