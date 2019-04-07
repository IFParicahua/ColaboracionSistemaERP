@extends('menus')
@section('content')
    <script>
        setTimeout(function() {
            $("#alerta1").fadeOut();
        },3000);
        setTimeout(function() {
            $("#alerta2").fadeOut();
        },3000);

        setTimeout(function() {
            $("#casa").fadeOut();
        },3000);
    </script>

    <script>
        $('body').on('shown.bs.modal','#new-cuenta',function () {
            $('input:visible:enabled:first',this).focus();
        })
    </script>
    <script>
        $('body').on('shown.bs.modal','#edit-cuenta',function () {
            $('input:visible:enabled:first',this).focus();
        })
    </script>

    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <div class="row ">

                <div id="articuloList"></div>
                <h4 class="card-title" style="padding-left: 15px;padding-top: 10px;padding-right:15px">Plan de Cuenta </h4>

                <style>
                    .isDisabled {
                        color: currentColor;
                        cursor: not-allowed;
                        opacity: 0.5;
                        text-decoration: none;
                        pointer-events: none;
                    }
                </style>

                <div class="col-5" style="padding: 0px">
                    <button type="button" class="btn btn-primary fa fa-plus-circle" data-toggle="modal" id="agregar" name="agregar" data-target="#new-cuenta" data-toggle="tooltip" title="Agregar"></button>
                    <button type="button" id="print" name="print" class="btn btn-primary btn-fill fa fa-print btn-info" data-toggle="tooltip" title="Imprimir"></button>
                    <button type="button" class="btn btn-primary btn-fill fa fa-pencil" data-toggle="modal" data-target="#edit-cuenta" data-toggle="tooltip" title="Editar"></button>
                    <a class="btn btn-danger fa fa-trash-o " href=" " data-confirm="�Esta seguro que desea eliminar?" id="btn_eliminar" name="btn_eliminar" title="Eliminar"></a>

                    <input value="{{Auth::user()->id}}" id="user" name="user" type="hidden">
                    <input value="{{Session('emp-id')}}" id="empresa" name="empresa" type="hidden">
                    <script>
                        $(document).ready(function(){
                            $('#print').click(function () {
                                var usuario = $('#user').val();
                                var empresa = $('#empresa').val();
                                javascript:window.open('http://localhost:8000/Reportes/cuenta.php?empresa='+empresa+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                            })
                        });
                    </script>

                </div>
                <input id="nenivel" name="nenivel" value="{{Session('emp-Nivel')}}" type="hidden">
                <div class="text-right">
                    <label>
                        @if(Session::has('casa'))
                            <h4><span id="casa">
                                    {!! session('casa') !!}
                                </span></h4>
                        @endif
                    </label>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12" style="width: 1004px;height: 490px; overflow: auto; ">
                    <div id="myjstree" class="container">
                        {!! $tree !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--******************Modal elimina******************************-->
    <script>
        $(document).ready(function() {
            $('a[data-confirm]').click(function(ev) {
                var href = $(this).attr('href');
                if (!$('#dataConfirmModal').length) {
                    $('body').append('<div class="modal fade in" id="dataConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: block;">' +
                        '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">�' +
                        '</button><h4 class="modal-title" id="myModalLabel" style="margin-right: 150px;">Advertencia</h4></div><div class="modal-body"></div><div class="modal-footer">' +
                        '<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button><a class="btn btn-primary" id="dataConfirmOK">Borrar</a></div>' +
                        '</div></div></div>');
                }
                $('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
                $('#dataConfirmOK').attr('href', href);
                $('#dataConfirmModal').modal({show:true});
                return false;
            });
        });
    </script>
    <!--******************Fin Modal elimina-->

    <script>

        var $treeview = $("#myjstree");
        $treeview
            .on('ready.jstree', function() {
                $treeview.jstree('open_all');
            });
        $('#myjstree').jstree({ "themes" : { "stripes" : true }});
        //evento click de jstree
        $('#myjstree').on("select_node.jstree", function (e, data) {
            var level=data.node.parents.length;
            //recuperar id de nodo seleccionado
            ////enviamos id a formulario nueva cuenta

            var nnivel=$("#nenivel").val();

            if ((data.node.id)>0 ){
                if (level == nnivel) {
                    $("#agregar").prop("disabled", true);
                }else {
                    $("#agregar").prop("disabled", false);
                    $("#idpadre").val(data.node.id);
                }
            } else
            {
                $("#agregar").prop("disabled", false);
                $("#idpadre").val("");
            }
            //Bloquear boton
            var idDelete = data.node.id;
            if(idDelete != '')
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('borrar.fetch') }}",
                    method:"POST",
                    data:{query:idDelete, _token:_token},
                    success:function(data){
                        if (data == 0){
                            $('#btn_eliminar').removeClass('isDisabled');
                        } else {
                            $('#btn_eliminar').addClass('isDisabled');
                        }
                    }
                });
            }

            ////enviamos id a formulario editar cuenta
            $("#id").val(data.node.id);

            $("#btn_eliminar").attr("href","http://localhost:8000/cuenta/"+(data.node.id)+"/delete");

            //recuperar nivel de nodo seleccionado

            var nivel= level + 1;
            $("#nivel").val(nivel);

            //obtener texto de li
            var cadena = data.node.text;
            //recuperar nombre de nodo seleccionado
            separador = "-", // un espacio en blanco
                arreglo = cadena.split(separador);
            $("#nombre").val(arreglo[1]);
        });


    </script>

    @if(!empty(Session::get('error_code')) && Session::get('error_code') == 5)
        <script>
            $(function() {
                $('#new-cuenta').modal('show');
            });
        </script>
    @endif
    @if(!empty(Session::get('error_code')) && Session::get('error_code') == 4)
        <script>
            $(function() {
                $('#edit-cuenta').modal('show');
            });
        </script>
    @endif
    @if(!empty(Session::get('error_code')) && Session::get('error_code') == 3)
        <script>
            $(function() {
                $('#modalError').modal('show');
            });
        </script>
    @endif
    <div class="modal fade" id="modalError" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">x</span>
                    </button>
                    <h4 class="modal-title" style="margin-right: 200px;margin-left: 150px;"><i class="fa fa-exclamation-triangle"></i>Error</h4>

                </div>
                <div class="modal-body">
                    No se puede eliminar
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAlerta" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">x</span>
                    </button>
                    <h4 class="modal-title" style="margin-right: 200px;margin-left: 150px;"><i class="fa fa-exclamation-triangle"></i>Error</h4>
                </div>
                <div class="modal-body">
                    Debe selecccionar un item
                </div>
            </div>
        </div>
    </div>


    <!-- Modal cuenta new -->
    <div class="modal fade col-lg-12" id="new-cuenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content card-body" >
                <div>
                    <h4 class="modal-title">Nueva Cuenta</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form" method="post" action="{{url('cuenta/create')}}" id="form-new">
                        {!! csrf_field() !!}
                        <div class="panel-body">
                            <input type="hidden" id="idpadre" name="idpadre">
                            <input type="hidden" id="nivel" name="nivel">

                            <div class="form-group col-md-9 pl-1" {{ $errors->has('nombres') ? ' has-error' : '' }}>
                                <label for="nombres" class="control-label">Nombre:</label>
                                <input type="text" class="form-control" id="nombres" name="nombres">
                                @if ($errors->has('nombres'))
                                    <span id="alerta1"  class="help-block">
                                        <label class="text-danger">{{ $errors->first('nombres') }}</label>
                                    </span>
                                @endif
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

    <!-- Modal cuenta edit -->
    <div class="modal fade col-lg-12" id="edit-cuenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content card-body" >
                <div>
                    <h4 class="modal-title">Editar Cuenta</h4>
                </div>
                <div class="modal-body">
                    <form onsubmit="return validacion()" data-toggle="validator"  role="form" method="post" action="{{url('cuenta/update')}}" >
                        {!! csrf_field() !!}
                        <div class="panel-body" >

                            <div class="form-group col-md-9 pl-1" {{ $errors->has('nombre') ? ' has-error' : '' }}>
                                <label for="nombre" class="control-label">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre">
                                @if ($errors->has('nombre'))
                                    <span id="alerta2"  class="help-block">
                                        <label class="text-danger">{{ $errors->first('nombre') }}</label>
                                    </span>
                                @endif
                            </div>
                            <input type="hidden" id="id" name="id" required>
                        </div>
                        <div class="modal-footer" >
                            <button type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary btn-fill">Guardar</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <script>
        //funcion para mostrar mensaje de error "Debe seleccionar elemento" para el formulario editar
        function validacion() {
            valor = document.getElementById("id").value;
            if( valor == null || valor.length == 0 || /^\s+$/.test(valor)|| valor == 0 ) {
                //Accion para mostrar mensaje
                //toastr.error("Debe seleccionar un elemento para poder editarlo");
                $('#modalAlerta').modal('show');
                return false;
            }
        }
        //funcion para mostrar mensaje de error "Debe seleccionar elemento" para el formulario eliminar

        function validaciones() {
            valor = document.getElementById("id").value;
            if( valor == null || valor.length == 0 || /^\s+$/.test(valor)|| valor == 0 ) {
                //Accion para mostrar mensaje
                //toastr.error("Debe seleccionar un elemento para poder Eliminarlo");
                $('#modalAlerta').modal('show');
                return false;
            }
        }
    </script>

@endsection