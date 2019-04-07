@extends('menu')
@section('content')
    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <div class="card-header ">
                <h4 class="template-demo">
                    <button type="button" class="btn btn-primary btn-fill fa fa-plus" data-toggle="modal" data-target="#new-empresa" data-toggle="tooltip" title="Agregar" id="nuevo"></button>
                    <button type="button" id="print" name="print" class="btn btn-primary btn-info fa fa-print" data-toggle="tooltip" title="Imprimir"></button>
                </h4>
                <input value="{{Auth::user()->id}}" id="user" name="user" type="hidden">
                <input value="{{Session('emp-id')}}" id="empresa" name="empresa" type="hidden">
                <script>
                    $(document).ready(function(){
                        $('#print').click(function () {
                            var usuario = $('#user').val();
                            var empresa = $('#empresa').val();
                            javascript:window.open('http://localhost:8000/Reportes/empresa.php?empresa='+empresa+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                        })
                    });
                </script>
                <!-- Modal Empresa new -->

            </div>
            <div class="card-body table-full-width table-responsive">
                @if(Session::has('danger'))
                    <script>
                        $(function() {
                            $('#error').modal('show');
                        });
                    </script>
                    <div id="error" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                        <div  style="margin-left: 76%;margin-right: 1%;" class="modal-dialog modal-sm " role="document">
                            <div class="modal-content alert-danger">
                                <div class="col-md-10 pl-1">
                                    <p2 style="color: #ff5963">No se pudo eliminar la empresa <b style="color: #ff5963">{!! session('danger') !!}</b> tiene gestiones incluidas</p2>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <table class="table table-hover table-striped">
                    <thead>
                    <th>Sigla</th>
                    <th>Nombre</th>
                    <th>NIT</th>
                    <th>Telefono</th>
                    <th>Correo</th>
                    <th>Nivel</th>
                    <th></th>
                    </thead>
                    <tbody>
                    @forelse($empresa as $empresa)
                        <tr class="data-row">
                            <td class="sigla">{{$empresa->Sigla}}</td>
                            <td class="razon">{{$empresa->Razon_social}}</td>
                            <td class="nit">{{$empresa->Nit}}</td>
                            <td class="telefono">{{$empresa->Telefono}}</td>
                            <td class="correo">{{$empresa->Correo}}</td>
                            <td class="nivel">{{$empresa->Niveles}}</td>
                            <td>
                                <form action="{{url('redirect')}}">
                                    <input id="id" name="id" type="hidden" value="{{$empresa->id}}" >
                                    <input id="name" name="name" type="hidden" value="{{$empresa->Razon_social}}">
                                    <a style="color: rgb(255,255,255)" id="edit-item" data-id="{{$empresa->id}}" data-direccion="{{$empresa->Direccion}}" class="btn btn-primary btn-fill fa fa-pencil" data-toggle="tooltip" title="Editar"></a>
                                    <a class="btn btn-danger btn-fill fa fa-trash-o" data-toggle="tooltip" title="Eliminar" href="empresa/{{$empresa->id}}/delete" data-confirm="¿Estas seguro que quieres borrar {{$empresa->Razon_social}}?"></a>
                                    <button type="submit" class="btn btn-success fa fa-check" data-toggle="tooltip" title="Redirigir"></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <div class="alert alert-danger" role="alert">No existen Empresas</div>
                    @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    @if(!empty(Session::get('error_code')) && Session::get('error_code') == 5)
        <script>
            $(function() {
                $('#new-empresa').modal('show');
            });
        </script>
    @endif
    @if(!empty(Session::get('error_code')) && Session::get('error_code') == 4)
        <script>
            $(function() {
                $('#edit-modal').modal('show');
            });
        </script>
    @endif
    <!-- *************Edit******************************** -->
    <script>
        $(document).ready(function() {

            $(document).on('click', "#edit-item", function() {
                $(this).addClass('edit-item-trigger-clicked'); //useful for identifying which trigger was clicked and consequently grab data from the correct row and not the wrong one.

                var options = {
                    'backdrop': 'static'
                };
                $('#edit-modal').modal(options)
            })

            // on modal show
            $('#edit-modal').on('show.bs.modal', function() {
                var el = $(".edit-item-trigger-clicked"); // See how its usefull right here?
                var row = el.closest(".data-row");

                // get the data
                var id = el.data('id');
                var direccion = el.data('direccion');
                var sigla = row.children(".sigla").text();
                var razon = row.children(".razon").text();
                var nit = row.children(".nit").text();
                var telefono = row.children(".telefono").text();
                var correo = row.children(".correo").text();
                var nivel = row.children(".nivel").text();


                // fill the data in the input fields
                //$("#razon_social").val(id);
                $("#pk-empresa").val(id);
                $("#siglas").val(sigla);
                $("#razon").val(razon);
                $("#nits").val(nit);
                $("#telefonos").val(telefono);
                $("#correos").val(correo);
                $("#direcciones").val(direccion);
                $("#niveles").val(nivel);

            })

            // on modal hide
            $('#edit-modal').on('hide.bs.modal', function() {
                $('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked')
                $("#edit-form").trigger("reset");
            });
        });
    </script>
@endsection
@section('model')

    <!-- javascript -->
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
    <!-- Modal Empresa new -->
    <div class="modal fade col-lg-12" id="new-empresa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" style="height: 50px;" role="document">
            <div class="modal-content card-body" >
                <div>
                    <h4 class="modal-title">Nueva Empresa</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form" method="post" action="{{url('empresa/create')}}" id="form-new">
                        {!! csrf_field() !!}
                        <div class="panel-body">

                            <input type="hidden" name="id_us" id="id_us" value="{{ Auth::user()->id }}">

                            <div class="row">
                                <div id="razon_social" class="form-group col-md-10 pl-1" {{ $errors->has('razon_social') ? ' has-error' : '' }}>
                                    <label for="razon_social" class="control-label">Razon Social:</label>
                                    <input type="text" class="form-control" id="razon_social" name="razon_social" maxlength="80" value="{{ old('razon_social') }}" required>
                                    @if ($errors->has('razon_social'))
                                        <span id="alerta"  class="help-block">
                                        <strong class="text-danger">{{ $errors->first('razon_social') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-5 pl-1 " {{ $errors->has('nit') ? ' has-error' : '' }}>
                                    <label for="nit" class="control-label">NIT:</label>
                                    <input type="number"  class="form-control" id="nit" name="nit" maxlength="8" value="{{ old('nit') }}" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"   required>
                                    @if ($errors->has('nit'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('nit') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group col-md-6 pl-1">
                                    <label for="moneda">Moneda:</label>
                                    <select class="form-control" id="moneda" name="moneda" value="{{ old('moneda') }}">
                                        @foreach($moneda as $monedas)
                                            <option value="{{$monedas->id}}">{{$monedas->Nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group col-md-4 pl-1" {{ $errors->has('sigla') ? ' has-error' : '' }} >
                                    <label for="sigla" class="control-label">Sigla:</label>
                                    <input type="text" class="form-control" id="sigla" name="sigla" value="{{ old('sigla') }}" maxlength="10" required>
                                    @if ($errors->has('sigla'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('sigla') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group col-md-5 pl-1">
                                    <label for="telefono" class="control-label">Telefono:</label>
                                    <input type="number" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" >
                                </div>
                                <div class="form-group col-md-3 pl-1">
                                    <label for="nivel">Nivel:</label>
                                    <select class="form-control" id="nivel" name="nivel" value="{{ old('nivel') }}">
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12 pl-1">
                                    <label for="correo" class="control-label">Correo:</label>
                                    <input type="email" class="form-control" id="correo" name="correo" value="{{ old('correo') }}">
                                </div>
                                <div class="form-group col-md-12 pl-1">
                                    <label for="direccion" class="control-label">Direccion:</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}">
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

    <!-- Modal Empresa Edit -->
    <div class="modal fade col-lg-12" id="edit-modal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content card-body" >
                <div>
                    <h4 class="modal-title">Editar Empresa</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form" method="post" action="{{url('empresa/update')}}" id="form-new">
                        {!! csrf_field() !!}
                        <div class="panel-body">

                            <input type="hidden" id="pk-empresa" name="pk-empresa" value="{{ old('pk-empresa') }}">

                            <div class="row">
                                <div class="form-group col-md-10 pl-1" {{ $errors->has('razon') ? ' has-error' : '' }}>
                                    <label for="razon" class="control-label">Razon Social:</label>
                                    <input type="text" class="form-control" id="razon" name="razon" maxlength="50" value="{{ old('razon') }}" required>
                                    @if ($errors->has('razon'))
                                        <span id="alerta"  class="help-block">
                                        <strong class="text-danger">{{ $errors->first('razon') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-5 pl-1 " {{ $errors->has('nits') ? ' has-error' : '' }}>
                                    <label for="nits" class="control-label">NIT:</label>
                                    <input type="number"  class="form-control" id="nits" name="nits" maxlength="8" value="{{ old('nits') }}" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"   required>
                                    @if ($errors->has('nits'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('nits') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4 pl-1" {{ $errors->has('siglas') ? ' has-error' : '' }} >
                                    <label for="siglas" class="control-label">Sigla:</label>
                                    <input type="text" class="form-control" id="siglas" name="siglas" value="{{ old('siglas') }}" maxlength="10" required>
                                    @if ($errors->has('siglas'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('siglas') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group col-md-5 pl-1">
                                    <label for="telefonos" class="control-label">Telefono:</label>
                                    <input type="number" class="form-control" id="telefonos" name="telefonos" value="{{ old('telefonos') }}" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" >
                                </div>

                                <div class="form-group col-md-3 pl-1">
                                    <label for="niveles">Nivel:</label>
                                    <select class="form-control" id="niveles" name="niveles" value="{{ old('niveles') }}">
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12 pl-1">
                                    <label for="correos" class="control-label">Correo:</label>
                                    <input type="email" class="form-control" id="correos" name="correos" value="{{ old('correos') }}">
                                </div>
                                <div class="form-group col-md-12 pl-1">
                                    <label for="direcciones" class="control-label">Direccion:</label>
                                    <input type="text" class="form-control" id="direcciones" name="direcciones" value="{{ old('direcciones') }}">
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