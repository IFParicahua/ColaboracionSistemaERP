@extends('menus')
@section('content')
    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <br>
            <div class="card-header ">
                <h4>Moneda
                    @foreach($mnempresas as $mnempresa)
                        @if ($mnempresa->Activo==1)
                            <a class="btn btn-primary btn-fill fa fa-pencil" id="edit-item" data-id="{{$mnempresa->idprincipal}}" data-inicio="{{$mnempresa->idalternativa}}" data-fin="{{$mnempresa->Cambio}}" style="color: #fff"></a>
                        @endif
                    @endforeach
                </h4>
            </div>
            <div class="card-body table-full-width table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <th>Principal</th>
                    <th>Alterna</th>
                    <th>Cambio</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    </thead>
                    <tbody>
                    @forelse($mnempresas as $mnempresa)
                        <tr class="data-row">
                            <td class="principal">{{$mnempresa->principal}}</td>
                            <td class="alterna">{{$mnempresa->alternativa}}</td>
                            <td class="cambio">{{$mnempresa->Cambio}}</td>
                            <td class="fecha" style="width: 223px;">{{date("d/m/Y H:i" , strtotime($mnempresa->FechaRegistro))}}</td>
                            <td>
                                @if ($mnempresa->Activo == 1)
                                    Activo
                                @else
                                    Desactivo
                                @endif
                            </td>
                        </tr>
                    @empty
                        <div class="alert alert-danger" role="alert">No existen Monedas</div>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('model')
    <script>$('#btn_add').click(function () {
            $('#formmonedas').trigger("reset");
            $('#new-moneda').modal('show');
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
                $('#new-moneda').modal(options)
            })

            // on modal show
            $('#new-moneda').on('show.bs.modal', function() {
                var el = $(".edit-item-trigger-clicked"); // See how its usefull right here?
                var row = el.closest(".data-row");

                // get the data
                var id = el.data('id');
                var inicio = el.data('inicio');
                var fin = el.data('fin');

                // fill the data in the input fields
                //$("#razon_social").val(id);
                $("#principal").val(id);
                $("#alternativa").val(inicio);
                $("#cambio").val(fin);
            })

            // on modal hide
            $('#new-moneda').on('hide.bs.modal', function() {
                $('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked')
                $("#edit-form").trigger("reset");
            });
        });


    </script>

    <!-- Modal Gestion new -->
    <div class="modal fade col-lg-12" id="new-moneda" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content card-body" >
                <div>
                    <h4 class="card-title">Nueva Moneda</h4>
                </div>
                <div class="modal-body">

                    <form data-toggle="validator"  role="form" method="post" action="{{url('moneda/create')}}" id="formmonedas" name="formmonedas">
                        {!! csrf_field() !!}
                        <div class="panel-body">
                            <div class="row">

                                <div class="form-group col-md-4 pl-1" >
                                    <label for="principal" >Principal:</label>
                                    <select class="form-control" id="principal" name="principal" value="{{ old('principal') }}">
                                        @foreach( $monedas as $moneda)
                                            <option value="{{$moneda->id}}">{{$moneda->Nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4 pl-1" >
                                    <label for="alternativa">Alternativa:</label>
                                    <select class="form-control" id="alternativa" name="alternativa" value="{{ old('alternativa') }}">
                                        @foreach( $monedas as $moneda)
                                            <option value="{{$moneda->id}}">{{$moneda->Nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4 pl-1" {{ $errors->has('cambio') ? ' has-error' : '' }}>
                                    <label for="cambio" class="control-label">Cambio:</label>
                                    <input type="text" class="form-control" id="cambio" name="cambio" value="{{ old('cambio') }}" required>
                                    @if ($errors->has('fechas_fin'))
                                        <span class="help-block">
                                            <label class="text-danger">{{ $errors->first('cambio') }}</label>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div id="val_alternativa"></div>
                        </div>
                        <div class="modal-footer" >
                            <button type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary btn-fill">Guardar</button>
                        </div>
                        <script>
                            $("#edit-item").click(function(event) {
                                $("#formmonedas")[0].reset();
                            });

                        </script>
                    </form>
                    <script>
                        $("#alternativa").click(function () {
                            //val_alternativa
                            var valor_alternativo = $("#alternativa").val();
                            var valor_principal  = $("#principal").val();
                            if ( valor_principal == valor_alternativo){
                                $('#val_alternativa').fadeIn();
                                $('#val_alternativa').html('<span id="alerta"  class="help-block"><label class="text-danger">No puede ser igual a la moneda alternativa</label></span>');
                            } else {
                                $('#val_alternativa').fadeOut();
                            }
                        });

                    </script>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@endsection