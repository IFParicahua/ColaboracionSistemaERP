@extends('menus')
@section('content')
    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <div class="panel-body">
                <div class="card-body" style="padding-top: 0px">
                    <form action="{{url('nota-lote/nuevo')}}" method=post name=test onSubmit=setValue()>
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-3 pl-1">
                                <h4 class="card-title">Nueva Nota de Compra</h4>
                            </div>
                            <div class="col-md-2 pl-0">
                                <button type="submit" class="btn btn-primary btn-fill fa fa-save" id="bt_grabar" name="bt_grabar" ></button>
                                <button type="button" class="btn btn-primary btn-fill fa fa-print" data-toggle="tooltip" title="Imprimir" onclick="javascript:window.open('http://localhost:8080/proyect002/public/Reportes/ReporteComprobantes.php','','width=900,height=500,left=50,top=50');"></button>
                            </div>
                            <input id="det_articulo" name="det_articulo" type="hidden" value="{{ old('det_articulo') }}">
                            <input id="det_precio" name="det_precio" type="hidden" value="{{ old('det_precio') }}">
                            <input id="det_cantidad" name="det_cantidad" type="hidden" value="{{ old('det_cantidad') }}">
                            <input id="det_total" name="det_total" type="hidden" value="{{ old('det_total') }}">
                            <input id="det_vencimiento" name="det_vencimiento" type="hidden" value="{{ old('det_vencimiento') }}">
                            <input id="det_cant" name="det_cant" type="hidden" value="{{ old('det_cant') }}">
                            <input id="val_periodo" name="val_periodo" type="hidden" value="{{ old('val_periodo') }}">
                        </div>
                        <div class="row">
                            <div class="col-md-2 pr-1" style="">
                                <div class="form-group">
                                    <label>Fecha</label>
                                    <input type="date" id="fecha_nota" name="fecha_nota" class="form-control" style="padding-left: 1px;padding-right: 0px"  onchange="val_fecha(event)" value="{{ old('fecha_nota') }}">
                                </div>
                            </div>
                            <div class="col-md-5 pl-1">
                                <div class="form-group">
                                    <label>Detalle</label>
                                    <input type="text" id="Glosa_nota" name="Glosa_nota" class="form-control" placeholder="Detalle"  value="{{ old('Glosa_nota') }}">
                                </div>
                            </div>
                            <div class="col-md-1 pl-1" style="padding-top: 22px">
                                <button type="button" class="btn btn-primary btn-fill fa fa-plus" id="btn_gr"  data-toggle="tooltip" title="Agregar"></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="LabelError" {{ $errors->has('fecha_nota') ? ' has-error' : '' }}>
                                @if ($errors->has('fecha_nota'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('fecha_nota') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-12" {{ $errors->has('val_periodo') ? ' has-error' : '' }}>
                                @if ($errors->has('val_periodo'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('val_periodo') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-12" id="LabProb" {{ $errors->has('Glosa_nota') ? ' has-error' : '' }}>
                                @if ($errors->has('Glosa_nota'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('Glosa_nota') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-12" id="ErrorGlosa">

                            </div>
                        </div>
                        <div class="row">
                            <div {{ $errors->has('det_articulo') ? ' has-error' : '' }}>
                                @if ($errors->has('det_articulo'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('det_articulo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </form>
                    <style>
                        thead, tbody { display: block;}
                        tbody {
                            height: 233px;
                            overflow-x: hidden;
                        }
                        .digito{
                            width: 80px;
                        }
                        .Articulo{
                            width: 300px;
                        }
                        .Precio{
                            width: 150px;
                        }
                        .Cantidad{
                            width: 150px;
                        }
                        .TotalSum{
                            width: 150px;
                        }
                        .Vencimiento{
                            width: 150px;
                        }
                        .Boton{
                            width: 80px;
                        }
                    </style>
                    <div class="card-body table-full-width table-responsive" style="padding-bottom: 0px;">
                        <table id="tabla" class="table table-hover table-striped">
                            <thead>
                            <th style="width: 80px"></th>
                            <th style="width: 300px">Articulo</th>
                            <th style="width: 150px">Precio</th>
                            <th style="width: 150px">Cantidad</th>
                            <th style="width: 150px">Subtotal</th>
                            <th style="width: 150px">Vencimiento</th>
                            <th style="width: 80px"></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <table>
                                <tbody style="height: 42px">
                                <tr>
                                    <td class="text-right" style="width: 620px;padding-right: 10px;font-family: 'Arial Black'; font-size: 17px">Total</td>
                                    <td style="width: 150px;"><input class="form-control" id="in_total" name="in_total" type="number" disabled></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('model')

    <!-- Modal Empresa new -->
    <div id="new-lote" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div >
                    <h4 class="card-title">Nuevo Lote</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form"  id="formcomprobante" name="formcomprobante">
                        {!! csrf_field() !!}
                        <div class="panel-body">
                            <input type="hidden" id="est_existe" name="est_existe" value="1">
                            <div class="row" style="height: 110px;">
                                <div class="form-group col-md-5 pl-1" >
                                    <label for="articulo" class="control-label">Articulo:</label>
                                    <input type="text" name="articulo" id="articulo" class="form-control" autocomplete="off"/>
                                    <div id="articuloList">
                                    </div>
                                    {{ csrf_field() }}

                                    <script>
                                        $(document).ready(function(){

                                            $('#articulo').keyup(function(){
                                                $("#est_existe").val("1");
                                                validar_vacio();
                                                var query = $(this).val();
                                                if(query != '')
                                                {
                                                    var _token = $('input[name="_token"]').val();
                                                    $.ajax({
                                                        url:"{{ route('articulo.fetch') }}",
                                                        method:"POST",
                                                        data:{query:query, _token:_token},
                                                        success:function(data){
                                                            $('#articuloList').fadeIn();
                                                            $('#articuloList').html(data);
                                                        }
                                                    });
                                                }
                                            });
                                            $(document).on('click', '.crear', function(){
                                                $('#articulo').val($(this).text());
                                                $('#est_existe').val($(this).text());
                                                $('#articuloList').fadeOut();
                                                existente();
                                                validar_vacio();
                                            });
                                        });
                                    </script>
                                </div>

                                <div class="form-group col-md-2 pl-1 " >
                                    <label for="precio" class="control-label">Precio:</label>
                                    <input type="number"  class="form-control" id="precio" name="precio" maxlength="8" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                </div>
                                <div class="form-group col-md-2 pl-1 " >
                                    <label for="cantidad" class="control-label">Cantidad:</label>
                                    <input type="number"  class="form-control" id="cantidad" name="cantidad" maxlength="8" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                </div>

                                <div class="form-group col-md-3 pl-1 " >
                                    <label for="vencimiento" class="control-label">Vencimiento:</label>
                                    <input type="date"  class="form-control" id="vencimiento" name="vencimiento" maxlength="8" >
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cerrar</button>
                            <button type="button" id="bt_add" class="btn btn-primary btn-fill" disabled>Guardar</button>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <!-- Modal Empresa new -->
    <div id="editar-lote" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div style="padding-left: 15px;background-color:#1E90FF ">
                    <h4 class="card-title" style="color: #FFFFFF;margin-top: 5px;margin-bottom: 5px; ">Editar Lote</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form"  id="formcomp_edit" name="formcomp_edit">
                        {!! csrf_field() !!}
                        <div class="panel-body">
                            <input type="hidden" id="variableModificando" name="variableModificando" value="">
                            <input type="text" id="est_existe_edit" name="est_existe_edit" value="1">
                            <div class="row" style="height: 110px;">
                                <div class="form-group col-md-5 pl-1" >
                                    <label for="articulo_edit" class="control-label">Articulo:</label>
                                    <input type="text" name="articulo_edit" id="articulo_edit" class="form-control" placeholder="" />
                                    <div id="articuloList_edit">
                                    </div>
                                    {{ csrf_field() }}

                                    <script>
                                        $(document).ready(function(){

                                            $('#articulo_edit').keyup(function(){
                                                existente();
                                                var query = $(this).val();
                                                if(query != '')
                                                {
                                                    var _token = $('input[name="_token"]').val();
                                                    $.ajax({
                                                        url:"{{ route('buscar.articulo') }}",
                                                        method:"POST",
                                                        data:{query:query, _token:_token},
                                                        success:function(data){
                                                            $('#articuloList_edit').fadeIn();
                                                            $('#articuloList_edit').html(data);
                                                        }
                                                    });
                                                }
                                            });
                                            $(document).on('click', '.editar', function(){
                                                $('#articulo_edit').val($(this).text());
                                                $('#articuloList_edit').fadeOut();
                                                existente();
                                            });
                                        });
                                    </script>
                                </div>

                                <div class="form-group col-md-2 pl-1 " >
                                    <label for="precio_edit" class="control-label">Precio:</label>
                                    <input type="number"  class="form-control" id="precio_edit" name="precio_edit" maxlength="8" >
                                </div>
                                <div class="form-group col-md-2 pl-1 " >
                                    <label for="cantidad_edit" class="control-label">Cantidad:</label>
                                    <input type="number"  class="form-control" id="cantidad_edit" name="cantidad_edit" maxlength="8" >
                                </div>

                                <div class="form-group col-md-3 pl-1 " >
                                    <label for="vencimiento_edit" class="control-label">Vencimiento:</label>
                                    <input type="date"  class="form-control" id="vencimiento_edit" name="vencimiento_edit" maxlength="8" >
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cerrar</button>
                            <button type="button" id="bt_edit" class="btn btn-primary btn-fill" >Guardar</button>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>


    <script>
        $(document).ready(function () {
            // $("#bt_edit").prop("disabled", true);
            $("#bt_grabar").prop("disabled", true);
            $('#btn_gr').click(function () {
                $('#new-lote').modal('show');
            });
            $('#bt_add').click(function () {
                agregar();
                sumaTotal();
                setValue();
                avilitar_guardado();
                $('#cantidad').val(" ");
                $('#precio').val(" ");
                $('#articulo').val(" ");
                $('#vencimiento').val(" ");
                $("#bt_add").prop("disabled", true);
                var fechform = $("#fecha_nota").val();

            });
            $('#bt_edit').click(function () {
                var fila = $("#variableModificando").val();
                var articulo=$("#articulo_edit").val();
                var precio=$("#precio_edit").val();
                var cantidad=$("#cantidad_edit").val();
                var vencimientos=$("#vencimiento_edit").val();
                var subtotal= precio * cantidad;
                document.getElementById("tabla").rows[fila].cells[1].innerHTML = articulo;
                document.getElementById("tabla").rows[fila].cells[2].innerHTML = precio;
                document.getElementById("tabla").rows[fila].cells[3].innerHTML = cantidad;
                document.getElementById("tabla").rows[fila].cells[4].innerHTML = subtotal;
                document.getElementById("tabla").rows[fila].cells[5].innerHTML = vencimientos;
                sumaTotal();
                $('#editar-lote').modal('hide');
            });
            $('#cantidad').keyup(function () {
                validar_vacio();
            });
            $('#precio').keyup(function () {
                validar_vacio();
            });

            $('#Glosa_nota').keyup(function () {
                avilitar_guardado();
                var fechform = $("#fecha_nota").val();

            });
        });

        var cont=0;

        function agregar() {
            cont++;
            var articulo=$("#articulo").val();
            var precio=$("#precio").val();
            var cantidad=$("#cantidad").val();
            var TotalSum= (precio * cantidad);
            var vencimiento=$("#vencimiento").val();
            var fila='<tr class="selected" id="fila'+cont+'"><td class="digito">'+cont+'</td>' +
                '<td class="Articulo">'+articulo+'</td><td class="Precio">'+precio+'</td><td class="Cantidad">'+cantidad+'</td><td class="TotalSum">'+TotalSum+'</td><td class="Vencimiento">'+vencimiento+'</td><td class="Boton">' +
                '<button type="button" value="Remove" onclick="removeRow(this)" class="btn btn-danger btn-fill fa fa-trash-o" style="color: #fff;padding-left: 6px;width: 30px;height: 31px;padding-bottom: 8px;padding-top: 4px;" data-toggle="tooltip" title="Eliminar"></button>' +
                '<button type="button" onclick="modificando(this)" class="btn btn-primary btn-fill fa fa-pencil" style="color: #fff;padding-left: 6px;width: 30px;height: 31px;padding-bottom: 8px;padding-top: 4px;" data-toggle="tooltip" title="Editar"></button>\n</td></tr>';
            $('#tabla').append(fila);
            $("#Debe").val("");
            $('#Haber').val("");
            $('#articulo').val("");
        }
        function sumaTotal() {
            var col=document.getElementById("tabla").rows.length;
            var columna = 1;
            var a = col - 1;
            var i;
            var debe_sum=new Array();
            var total_debe = 0;

            for (i = 0; i < a; i++) {
                debe_sum[i]= parseFloat(document.getElementById("tabla").rows[columna].cells[4].innerHTML);
                total_debe = parseFloat(debe_sum[i]) + parseFloat(total_debe);
                columna++;
            }

            $("#det_total").val(total_debe);
            $("#in_total").val(total_debe);
            setValue();
            avilitar_guardado();
        }
        function existente() {
            var columnas=document.getElementById("tabla").rows.length;
            var val_cuenta = $('#articulo').val();
            var cuentas;
            var ct = 0;
            var columna = 1;
            var a = columnas - 1;
            var i;
            for (i = 0; i < a; i++) {
                cuentas =document.getElementById("tabla").rows[columna].cells[1].innerHTML;
                if (cuentas == val_cuenta) {
                    ct++;
                }else {
                    columna++;
                }
            }
            if (ct != 0){
                $("#est_existe").val("2");
                toastr.error("Este Articulo ya existe");
            }else {
                $("#est_existe").val("3");
            }
        }
        function setValue()
        {
            var articulos=new Array();
            var precios=new Array();
            var cantidades=new Array();
            var vencimientos=new Array();
            var col=document.getElementById("tabla").rows.length;
            var columna = 1;
            var a = col - 1;
            var i;
            for (i = 0; i < a; i++) {
                articulos[i]=document.getElementById("tabla").rows[columna].cells[1].innerHTML;
                precios[i]=document.getElementById("tabla").rows[columna].cells[2].innerHTML;
                cantidades[i]=parseInt(document.getElementById("tabla").rows[columna].cells[3].innerHTML);
                vencimientos[i]=$("#fila" + (i + 1) + " .Vencimiento").text();
                columna++;
            }
            var articulo = articulos.toString();
            var precio = precios.toString();
            var cantidad = cantidades.toString();
            var vencimiento = vencimientos.toString();


            $("#det_articulo").val(articulo);
            $("#det_precio").val(precio);
            $("#det_cantidad").val(cantidad);
            $("#det_vencimiento").val(vencimiento);
            $("#det_cant").val(a);
        }

        function modificando(numero){
            $('#editar-lote').modal('show');
            var fila = numero.parentNode.parentNode.rowIndex;
            var articulos=document.getElementById("tabla").rows[fila].cells[1].innerHTML;
            var precios=document.getElementById("tabla").rows[fila].cells[2].innerHTML;
            var cantidades=document.getElementById("tabla").rows[fila].cells[3].innerHTML;
            var vencimientos=document.getElementById("tabla").rows[fila].cells[5].innerHTML;

            $('#articulo_edit').html(articulos);
            $("#precio_edit").val(precios);
            $("#cantidad_edit").val(cantidades);
            $("#vencimiento_edit").val(vencimientos);
            $("#variableModificando").val(fila);
        }
        function removeRow(oButton) {
            var empTab = document.getElementById('tabla');
            empTab.deleteRow(oButton.parentNode.parentNode.rowIndex);
            sumaTotal();
            desblock();
        }
        function validar_vacio() {
            var estado = $("#est_existe").val();
            var articulo = $('#articulo').val();
            var precio = $('#precio').val();
            var cantidad = $('#cantidad').val();
            if (  precio == 0 || cantidad == 0 || estado < 3 ){
                $("#bt_add").prop("disabled", true);
            } else {
                $("#bt_add").prop("disabled", false);
                {{--var _token = $('input[name="_token"]').val();--}}
                {{--$.ajax({--}}
                {{--url:"{{ route('articulo.valido') }}",--}}
                {{--method:"POST",--}}
                {{--data:{query:articulo, _token:_token},--}}
                {{--success:function(data){--}}
                {{--if (data == 2){--}}
                {{--$("#bt_add").prop("disabled", true);--}}
                {{--} else {--}}
                {{--$("#bt_add").prop("disabled", false);--}}
                {{--}--}}
                {{--}--}}
                {{--});--}}
            }

        }

        function avilitar_guardado() {
            var fecha_nota = $("#fecha_nota").val();
            var glosa_nota = $('#Glosa_nota').val();
            var cantidad_gl = $('#det_cant').val();
            var est = $('#val_periodo').val();
            var a = 0;

            if (cantidad_gl == 0){
                if (glosa_nota == 0){
                    a = 0;
                }else {
                    a = 1;
                }
            }else {
                if (glosa_nota == 0){
                    a = 1 ;
                }else {
                    a = 2;
                }
            }

            if(a == 2 && est == 1){
                $("#bt_grabar").prop("disabled", false);
            }else {
                $("#bt_grabar").prop("disabled", true);
            }

            if (fecha_nota == 0) {
                $('#LabelError').fadeIn();
                $('#LabelError').html("Campo Fecha Obligatorio");
            }
            // if (glosa_nota == 0){
            //     $('#ErrorGlosa').fadeIn();
            //     $('#ErrorGlosa').html("Campo Glosa Obligatorio");
            // }else {
            //     $('#ErrorGlosa').fadeOut();
            // }
        }

        //Corregido Validar fecha dentro de periodo si existe integracion
        function val_fecha(e) {
            var fecha = e.target.value;
            $.ajax({
                url:"{{ route('validar.fecha') }}",
                method:'GET',
                data:{query:fecha },
                dataType:'json',
                success:function(data)
                {
                    $("#val_periodo").val(data);
                    avilitar_guardado();
                    if (data == 2 ){
                        $('#LabelError').html('La fecha  no se encuentra dentro de un periodo');
                    }else {
                        $('#LabelError').html(' ');
                    }
                }
            });
        }
    </script>
@endsection