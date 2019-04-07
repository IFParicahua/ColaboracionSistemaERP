@extends('menus')
@section('content')
    <script>
        $('body').on('shown.bs.modal', '#formcategoria', function () {
            $('input:visible:enabled:first', this).focus();
        })
    </script>
    <script>
        $('#alerta').fadeIn();
        setTimeout(function() {
            $("#casa").fadeOut();
        },3000);
        setTimeout(function() {
            $("#holas").fadeOut();
        },3000);
        setTimeout(function() {
            $("#verde").fadeOut();
        },3000);
    </script>

    <div class="col-lg-12">
        <div class="card">
            <div>
                <div>
                    <h4>Categoria  </h4>
                    <button id="btn_add" name="btn_add" class="btn btn-primary fa fas fa-plus text-right"></button>
                    <button  type="button" class="btn btn-primary fa fas fa-edit" id="btn_edit" name="btn_edit" data-toggle="tooltip" ></button>
                    <button type="button"  id="btnrep" name="btnrep" class="btn btn-primary fa fas fa-print text-right"></button>
                    <input type="hidden" id="empresa" name="empresa" value="{{Session('emp-id')}}">
                    <input type="hidden" id="usuario" name="usuario" value="{{Auth::user()->id}}">
                    <script>
                        $("#btnrep").click(function () {
                            var empresa= $("#empresa").val();
                            var usuario= $("#usuario").val();
                            javascript:window.open('http://localhost:8000/Reportes/categoria.php?empresa='+empresa+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                        })
                    </script>
                    <a class="btn btn-danger fa fa-trash-o" id="btn_eliminar" name="btn_eliminar" data-toggle="tooltip"  href="" data-confirm="¿Estas seguro que quieres borrar esta categoria?"></a>
                </div>
            </div>
            <div id="contenedor" class="card-body">
                <div class="table-responsive">
                    <div id="myjstree" class="container">
                        {!! $tree !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var $myjstree = $("#myjstree");
        $myjstree
            .on('loaded.jstree', function(){
                $myjstree.jstree('open_all');
            });

        $('#myjstree').jstree({ "themes" : { "stripes" : true }}).on("select_node.jstree", function (e, data) {
            //Guardar Id del padre
            if ((data.node.id)>0){
                $("#idpadre").val(data.node.id);
            }else {
                $("#idpadre").val("");
            }
            $("#nombre").val(data.node.text);
            $("#idcategoria").val(data.node.id);
            $("#btn_eliminar").attr("href", "http://localhost:8000/categoria/"+ (data.node.id)+"/eliminar");
        });
        $('#btn_add').click(function () {
            $('#formcuenta').trigger("reset");
            $('#new-categoria').modal('show');
        });
    </script>
    @if(!empty(Session::get('error_code')) && Session::get('error_code') == 5)
        <script>
            $(function() {
                $('#new-categoria').modal('show');
            });
        </script>
    @endif
    @if(!empty(Session::get('error_code')) && Session::get('error_code') == 4)
        <script>
            $(function() {
                $('#edit-categoria').modal('show');
            });
        </script>
    @endif
    @if(!empty(Session::get('error_code')) && Session::get('error_code') == 3)
        <script>
            $(function() {
                $('#borrarcuenta').modal('show');
            });
        </script>
    @endif
    <!-- Modal Categoria new -->
    <div class="modal fade" id="new-categoria" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">Nueva Categoria</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form class=form-validate novalidate="novalidate" method="post" action="{{url('categoria/guardar')}}" id="formcategoria" name="formcategoria">
                        {!! csrf_field() !!}
                        <input type="hidden" id="idpadre" name="idpadre" value="{{ old('idpadre') }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group mb-6" {{ $errors->has('nombres') ? ' has-error' : '' }}>
                                        <label for="nombres" class="form-label">Nombre:</label>
                                        <input type="text"   value="{{ old('nombres') }}"class="form-control" id="nombres" name="nombres" required="" data-msg="Ingrese nombre de categoria">
                                        @if ($errors->has('nombres'))
                                            <span id="holas" class="help-block">
                                        <label class="text-danger">{{ $errors->first('nombres') }}</label>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div id="contenFecha"></div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group mb-6" {{ $errors->has('nombres') ? ' has-error' : '' }}>
                                        <label for="descripciones" class="form-label">Descripcion:</label>
                                        <input type="text"  class="form-control" id="descripciones" name="descripciones" required="" data-msg="Ingrese descripcion">
                                        @if ($errors->has('nombres'))
                                            <span id="holas" class="help-block">
                                        <label class="text-danger">{{ $errors->first('nombres') }}</label>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="btn_add" class="btn btn-primary btn-fill">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Categoria edit -->
    <div class="modal fade" id="edit-categoria" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">Editar categoria</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form class=form-validate novalidate="novalidate" method="post" action="{{url('categoria/actualizar')}}"   role="form" id="formcategoria" name="formcategoria">
                        {!! csrf_field() !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group mb-6" {{ $errors->has('nombre') ? ' has-error' : '' }}>
                                        <label for="nombre" class="form-label">Nombre:</label>
                                        <input type="hidden"  name="idcategoria" id="idcategoria">
                                        <input type="text"  class="form-control" id="nombre" name="nombre" required="" data-msg="Ingrese nombre de categoria">
                                        @if ($errors->has('nombre'))
                                            <span id="holas" class="help-block">
                                        <label class="text-danger">{{ $errors->first('nombre') }}</label>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group mb-6" {{ $errors->has('descripcion') ? ' has-error' : '' }}>
                                        <label for="descripcion" class="control-label">Descripcion:</label>
                                        <input type="text"  class="form-control" id="descripcion" name="descripcion" required="" data-msg="Ingrese descripcion">
                                        @if ($errors->has('nombre'))
                                            <span  class="help-block">
                                        <label class="text-danger">{{ $errors->first('nombre') }}</label>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-fill">Modificar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="borrarcuenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" class="modal fade text-left" style="display: none;" aria-hidden="true">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">Error al borrar</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-12">
                            <div class="form-group col-mb-4">
                                <label class="form-label">No se puede borrar la categoria</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#btn_edit').click(function(){
                var id = $('#idcategoria').val();
                if(id != '')
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('categoria.fetch') }}",
                        method:"POST",
                        data:{id:id, _token:_token},
                        success:function(data){
                            $('#descripcion').val(data);
                            $('#edit-categoria').modal('show');
                        }});
                }});

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
        })
    </script>





@endsection