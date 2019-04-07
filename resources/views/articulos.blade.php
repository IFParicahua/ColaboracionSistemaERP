@extends('menus')
@section('content')
    <script>
        $('#cantidad_edit').keyup(function () {
            validar_vacio_edit();
        });
        $("#precio_edit").keyup(function () {
            validar_vacio_edit();
        });

        function validar_vacio_edit() {
            var estado = $("#est_existe_edit").val();
            var articulo = $('#articulo_edit').val();
            var precio = $('#precio_edit').val();
            var cantidad = $('#cantidad_edit').val();
            if (  precio == 0 || cantidad == 0 || estado == 2){
                $("#bt_edit").prop("disabled", true);
            } else {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('articulo.valido') }}",
                    method:"POST",
                    data:{query:articulo, _token:_token},
                    success:function(data){
                        if (data == 2){
                            $("#bt_edit").prop("disabled", true);
                        } else {
                            $("#bt_edit").prop("disabled", false);
                        }
                    }
                });
            }
        }
    </script>
    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <br>
            <div class="card-header ">
                <h3 class="card-title">Articulo
                    <button id="btn_add" name="btn_add" class="btn btn-primary btn-fill fa fa-plus"></button>
                    <button type="button" id="print" name="print" class="btn btn-primary btn-fill fa fa-print" data-toggle="tooltip" title="Imprimir" ></button>
                </h3>
                <div id="PruevaDIV"></div>
                <input value="{{Auth::user()->id}}" id="user" name="user" type="hidden">
                <input value="{{Session('emp-id')}}" id="empresa" name="empresa" type="hidden">
                <script>
                    $(document).ready(function(){
                        $('#print').click(function () {
                            var usuario = $('#user').val();
                            var empresa = $('#empresa').val();
                            javascript:window.open('http://localhost:8000/Reportes/articulo.php?empresa='+empresa+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                        })
                    });
                </script>
            </div>
            <div class="card-body table-full-width table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Precio de Venta</th>
                    <th>Cantidad</th>
                    <th></th>
                    </thead>
                    <tbody>
                    @forelse($articulos as $articulo)
                        <tr class="data-row">
                            <td class="nombre">{{$articulo->Nombre}}</td>
                            <td class="descripcion">{{$articulo->Descripcion}}</td>
                            <td class="precio">{{$articulo->PrecioVenta}}</td>
                            <td class="cantidad">{{$articulo->Cantidad}}</td>
                            <td>
                                <button class="btn btn-primary btn-fill fa fa-pencil" id="bt_editar"  style="color: #ffffff;" onclick="funcion_editar({{$articulo->id}})"></button>
                                {{--<a class="btn btn-primary btn-fill fa fa-pencil" id="edit-item" style="color: #ffffff;" data-id="{{$articulo->id}}" data-categoria="{{$articulo->PkCategoria}}"></a>--}}
                                <a class="btn btn-danger btn-fill fa fa-trash-o" data-toggle="tooltip" title="Eliminar" href="articulo/{{$articulo->id}}/eliminar" data-confirm="�Estas seguro que quieres borrar el articulo {{$articulo->Nombre}}?"></a>
                                <a class="btn btn-success btn-fill fa fa-list" data-toggle="tooltip" title="Lote" href="lotes/{{$articulo->id}}"></a>
                            </td>
                        </tr>
                    @empty
                        <div class="alert alert-danger" role="alert">No existen Articulos</div>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('model')
    <!-- Modal Articulo new -->
    <div class="modal fade col-lg-12" id="new-articulo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" style="height: 50px;" role="document">
            <div class="modal-content col-md-20" >
                <div >
                    <h4 class="card-title">Nueva Articulo</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form" method="post" action="{{url('articulo/nuevo')}}" id="form-articulo" name="form-articulo">
                        {!! csrf_field() !!}
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-md-6 pl-1" {{ $errors->has('nombre') ? ' has-error' : '' }}>
                                    <label for="nombre" class="control-label">Nombre:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" maxlength="15" value="{{ old('nombre') }}" required>
                                    @if ($errors->has('nombre'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('nombre') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group col-md-6 pl-1" {{ $errors->has('descripcion') ? ' has-error' : '' }}>
                                    <label for="descripcion" class="control-label">Descripcion:</label>
                                    <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{ old('descripcion') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 pl-1" >
                                    <label for="categoria" class="control-label">Categoria:</label>
                                    <input type="text" name="categoria" id="categoria" class="form-control" placeholder="" value="{{ old('categoria') }}"  autocomplete="off"/>
                                    <input type="hidden" id="pkcategoria" name="pkcategoria" placeholder="" value="{{ old('pkcategoria') }}"/>
                                    <div id="categoriaList"></div>
                                    {{ csrf_field() }}
                                    <script>
                                        $(document).ready(function(){

                                            $('#categoria').keyup(function(){
                                                var query = $(this).val();
                                                if(query != '')
                                                {
                                                    var _token = $('input[name="_token"]').val();
                                                    $.ajax({
                                                        url:"{{ route('categoria.fetch') }}",
                                                        method:"POST",
                                                        data:{query:query, _token:_token},
                                                        success:function(data){
                                                            $('#categoriaList').fadeIn();
                                                            $('#categoriaList').html(data);
                                                        }
                                                    });
                                                }
                                            });
                                            $(document).on('click', 'li', function(){
                                                $('#pkcategoria').val($(this).attr("id"));
                                                $('#categoria').val($(this).text());
                                                $('#categoriaList').fadeOut();
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="form-group col-md-6 pl-1" {{ $errors->has('precio_venta') ? ' has-error' : '' }}>
                                    <label for="precio_venta" class="control-label">Precio de venta:</label>
                                    <input type="text" class="form-control" id="precio_venta" name="precio_venta" value="{{ old('precio_venta') }}" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" required >
                                </div>
                            </div>
                            <div class="row" {{ $errors->has('pkcategoria') ? ' has-error' : '' }} >
                                @if ($errors->has('pkcategoria'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('pkcategoria') }}</strong>
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
    <!-- Modal Articulo edit -->
    <div class="modal fade col-lg-12" id="edit-articulo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" style="height: 50px;" role="document">
            <div class="modal-content col-md-20" >
                <div>
                    <h4 class="card-title" style="color: #FFFFFF;margin-top: 5px;margin-bottom: 5px; ">Editar Articulo</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form" method="post" action="{{url('articulo/edit')}}" id="form-edit" name="form-edit">
                        {!! csrf_field() !!}
                        <div class="panel-body">
                            <div class="row">
                                <input type="hidden" class="form-control" id="pkarticulo" name="pkarticulo" maxlength="15" value="{{ old('pkarticulo') }}" required>

                                <div class="form-group col-md-6 pl-1" {{ $errors->has('edit_nombres') ? ' has-error' : '' }}>
                                    <label for="edit_nombres" class="control-label">Nombre:</label>
                                    <input type="text" class="form-control" id="edit_nombres" name="edit_nombres" maxlength="15" value="{{ old('edit_nombres') }}" required>
                                    @if ($errors->has('edit_nombres'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('edit_nombres') }}</strong>
                                    </span>
                                    @endif

                                </div>
                                <div class="form-group col-md-6 pl-1" >
                                    <label for="edit_descripciones" class="control-label">Descripcion:</label>
                                    <input type="text" class="form-control" id="edit_descripciones" name="edit_descripciones" value="{{ old('edit_descripciones') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 pl-1" >
                                    <label for="edit_categorias" class="control-label">Categoria:</label>
                                    <input type="text" name="edit_categorias" id="edit_categorias" class="form-control" placeholder="" value="{{ old('edit_categorias') }}" autocomplete="off"/>
                                    <input type="hidden" id="pk_edit_categ" name="pk_edit_categ" placeholder="" value="{{ old('pk_edit_categ') }}"/>
                                    <div id="categoriaEdit"></div>
                                    {{ csrf_field() }}
                                    <script>
                                        $(document).ready(function(){

                                            $('#edit_categorias').keyup(function(){
                                                $('#pk_edit_categ').val(" ");
                                                var query = $(this).val();
                                                if(query != '')
                                                {
                                                    var _token = $('input[name="_token"]').val();
                                                    $.ajax({
                                                        url:"{{ route('articulo.completar') }}",
                                                        method:"POST",
                                                        data:{query:query, _token:_token},
                                                        success:function(data){
                                                            $('#categoriaEdit').fadeIn();
                                                            $('#categoriaEdit').html(data);
                                                        }
                                                    });
                                                }
                                            });
                                            $(document).on('click', '.art', function(){
                                                $('#categoriaEdit').fadeOut();
                                                $('#pk_edit_categ').val($(this).attr("id"));
                                                $('#edit_categorias').val($(this).text());

                                            });
                                        });
                                    </script>
                                </div>
                                <div class="form-group col-md-6 pl-1" >
                                    <label for="precio" class="control-label">Precio de venta:</label>
                                    <input type="text" class="form-control" id="precio" name="precio" value="{{ old('precio') }}" required>
                                </div>
                            </div>
                            <div class="row" {{ $errors->has('pk_edit_categ') ? ' has-error' : '' }} >
                                @if ($errors->has('pk_edit_categ'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('pk_edit_categ') }}</strong>
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
    <script>
        $(document).ready(function() {
            $('#btn_add').click(function () {
                $('#form-articulo').trigger("reset");
                $('#new-articulo').modal('show');
            });

            //Eliminar
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

        function funcion_editar(e) {
            var id = e;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('edit_articulo.fetch') }}",
                method: "POST",
                data: {query: id, _token: _token},
                success: function (data) {
                    // $('#PruevaDIV').html(data+"-"+id);
                    separador = ",",
                        arreglo = data.split(separador);
                    $('#edit-articulo').modal('show');
                    $('#pkarticulo').val(id);
                    $('#edit_nombres').val(arreglo[0]);
                    $('#pk_edit_categ').val(arreglo[1]);
                    $('#edit_categorias').val(arreglo[2]);
                    $('#edit_descripciones').val(arreglo[3]);
                    $('#precio').val(arreglo[4]);
                }
            });
        }

    </script>
    @if(!empty(Session::get('error_code')) && Session::get('error_code') == 4)
        <script>
            $(function() {
                $('#edit-articulo').modal('show');
            });
        </script>
    @endif
    @if(!empty(Session::get('error_code')) && Session::get('error_code') == 5)
        <script>
            $(function() {
                $('#new-articulo').modal('show');
            });
        </script>
    @endif
@endsection
