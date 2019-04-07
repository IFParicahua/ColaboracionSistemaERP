@extends('menus')
@section('content')
    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <div class="panel-body">
                <div class="card-body" style="padding-top: 0px">
                    <br>
                    <form action="{{url('detalle-nuevo/nuevo')}}" method=post name=test onSubmit=setValue()>
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-3 pl-1">
                                <h4 class="card-title">Nueva Nota de Venta</h4>
                            </div>
                            <div class="col-md-2 pl-0">
                                <button type="submit" class="btn btn-primary btn-fill fa fa-save" id="bt_grabar" name="bt_grabar"></button>
                                <button type="button" class="btn btn-primary btn-fill fa fa-print" data-toggle="tooltip" title="Imprimir" onclick="javascript:window.open('http://localhost:8080/proyect002/public/Reportes/ReporteComprobantes.php','','width=900,height=500,left=50,top=50');"></button>
                            </div>
                            <input id="det_articulo" name="det_articulo" type="hidden">
                            <input id="det_precio" name="det_precio" type="hidden">
                            <input id="det_cantidad" name="det_cantidad" type="hidden">
                            <input id="det_lote" name="det_lote" type="hidden">
                            <input id="det_total" name="det_total" type="hidden">
                            <input id="det_cant" name="det_cant" type="hidden">
                            <input id="val_periodo" name="val_periodo" type="hidden">
                        </div>
                        <div class="row">
                            <div class="col-md-2 pr-1" style="">
                                <div class="form-group">
                                    <label>Fecha</label>
                                    <input type="date" id="fecha_nota" name="fecha_nota" class="form-control" style="padding-left: 1px;padding-right: 0px" onchange="validacion_form_fecha(event);" onkeyup="$('#val_periodo').val('2')">
                                </div>
                            </div>
                            <div class="col-md-5 pl-1">
                                <div class="form-group">
                                    <label>Detalle</label>
                                    <input type="text" id="Glosa_nota" name="Glosa_nota" class="form-control" placeholder="Detalle" value="">
                                </div>
                            </div>
                            <div class="col-md-1 pl-1" style="padding-top: 22px">
                                <button type="button" class="btn btn-primary btn-fill fa fa-plus" id="btn_gr"  data-toggle="tooltip" title="Agregar"></button>
                            </div>
                        </div>
                        <div class="row"><label id="LabelError"></label></div>
                    </form>
                    <style>
                        thead, tbody { display: block;}
                        tbody {
                            height: 233px;
                            overflow-x: hidden;
                        }
                        .Articulo{
                            width: 338px;
                        }
                        .Precio{
                            width: 175px;
                        }
                        .Cantidad{
                            width: 175px;
                        }
                        .TotalSum{
                            width: 175px;
                        }
                        .Boton{
                            width: 80px;
                        }
                    </style>
                    <div class="card-body table-full-width table-responsive" style="padding-bottom: 0px;">
                        <table id="tabla" class="table table-hover table-striped">
                            <thead>
                            <th style="width: 338px" class="Articulo">Articulo</th>
                            <th style="width: 175px">Precio</th>
                            <th style="width: 175px">Cantidad</th>
                            <th style="width: 175px">Subtotal</th>
                            <th style="display: none">Lote</th>
                            <th style="width: 80px"></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <table>
                                <tbody style="height: 42px;width: 800px;">
                                <tr>
                                    <td class="text-right" style="width: 650px;padding-right: 10px;font-family: 'Arial Black'; font-size: 17px">Total</td>
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
                <div>
                    <h4 class="card-title">Nuevo Lote</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form"  id="formdetalleventa" name="formdetalleventa">
                        {!! csrf_field() !!}
                        <div class="panel-body">
                            <input type="hidden" id="est_existe" name="est_existe" >
                            <input type="hidden" id="form_stock" name="form_stock" value="1">
                            <div class="row" style="height: 110px;">
                                <div class="form-group col-md-5 pl-1" >
                                    <label for="articulo" class="control-label">Articulo:</label>
                                    <input type="text" name="articulo" id="articulo" class="form-control" placeholder="" autocomplete="off"/>
                                    <div id="articuloList">
                                    </div>
                                    {{ csrf_field() }}
                                    <script>
                                        $(document).ready(function(){

                                            $('#articulo').keyup(function(){
                                                $('#cantidad').val("");
                                                $("#est_existe").val('2');
                                                validar_vacios();
                                                var query = $(this).val();
                                                if(query != '')
                                                {
                                                    var _token = $('input[name="_token"]').val();
                                                    $.ajax({
                                                        url:"{{ route('articulo.buscar') }}",
                                                        method:"POST",
                                                        data:{query:query, _token:_token},
                                                        success:function(data){
                                                            $('#articuloList').fadeIn();
                                                            $('#articuloList').html(data);
                                                        }
                                                    });
                                                }
                                            });
                                            $(document).on('click', '.nuevo', function(){
                                                $('#articulo').val($(this).text());
                                                $('#articuloList').fadeOut();
                                                buscar_lote($(this).attr("id"));
                                                existente();
                                                validar_vacios();
                                                var loteform =$("#lotes").val();
                                                var cantform = $("#cantidad").val();
                                                validacion_form_cantidad(cantform , loteform);
                                            });
                                        });
                                    </script>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label for="roll">Lote: </label>
                                        <select name="lotes" class="form-control" id="lotes" value="" disabled>
                                        </select>
                                    </div>
                                    <div id="divLote"> </div>
                                </div>

                                <div class="form-group col-md-2 pl-1 " >
                                    <label for="precio" class="control-label">Precio:</label>
                                    <input type="number"  class="form-control" id="precio" name="precio" maxlength="8" >
                                </div>
                                <div class="form-group col-md-2 pl-1 " >
                                    <label for="cantidad" class="control-label">Cantidad:</label>
                                    <input type="number"  class="form-control" id="cantidad" name="cantidad" maxlength="8" >
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cerrar</button>
                            <button type="button" id="bt_add" class="btn btn-primary btn-fill"  disabled>Guardar</button>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <!-- Modal Empresa edit -->
    <div id="edit-detalle" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div style="padding-left: 15px;background-color:#1E90FF ">
                    <h4 class="card-title" style="color: #FFFFFF;margin-top: 5px;margin-bottom: 5px; ">Editar detalle</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form"  id="formdetalleventa" name="formdetalleventa">
                        {!! csrf_field() !!}
                        <div class="panel-body">
                            <input type="hidden" id="variableModificando" >
                            <input type="hidden" id="variableIG">
                            <input type="hidden" id="est_existes" name="est_existes" value="1">
                            <input type="hidden" id="stock_edit" name="stock_edit" value="2">

                            <div class="row" style="height: 110px;">
                                <div class="form-group col-md-5 pl-1" >
                                    <label for="edit_articulo" class="control-label">Articulo:</label>
                                    <input type="text" name="edit_articulo" id="edit_articulo" class="form-control" placeholder="" />
                                    <div id="ListArticulo_edit">
                                    </div>
                                    {{ csrf_field() }}

                                    <script>
                                        $(document).ready(function(){

                                            $('#edit_articulo').keyup(function(){
                                                $('#stock_edit').val("");
                                                $('#est_existes').val("2");
                                                $("#edit_lote").prop("disabled", true);
                                                existente();
                                                validar_vacios_edit();
                                                var query = $(this).val();
                                                if(query != '')
                                                {
                                                    var _token = $('input[name="_token"]').val();
                                                    $.ajax({
                                                        url:"{{ route('search.articulo') }}",
                                                        method:"POST",
                                                        data:{query:query, _token:_token},
                                                        success:function(data){
                                                            $('#ListArticulo_edit').fadeIn();
                                                            $('#ListArticulo_edit').html(data);
                                                        }
                                                    });
                                                }
                                            });
                                            $(document).on('click', '.editar', function(){
                                                $('#edit_articulo').val($(this).text());
                                                $('#ListArticulo_edit').fadeOut();
                                                $('#est_existes').val("1");
                                                existente();
                                                buscar_lote_edit($(this).attr("id"));
                                                validar_vacios_edit();
                                            });
                                        });
                                    </script>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label for="roll">Lote: </label>
                                        <select name="edit_lote" class="form-control" id="edit_lote" value="">

                                        </select>
                                    </div>
                                    <div id="divLoteError"> </div>
                                </div>

                                <div class="form-group col-md-2 pl-1 " >
                                    <label for="edit_precio" class="control-label">Precio:</label>
                                    <input type="text"  class="form-control" id="edit_precio" name="edit_precio" maxlength="8" >
                                </div>
                                <div class="form-group col-md-2 pl-1 " >
                                    <label for="edit_cantidads" class="control-label">Cantidad:</label>
                                    <input type="number"  class="form-control" id="edit_cantidads" name="edit_cantidads">
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cerrar</button>
                            <button type="button" id="bt_edit" class="btn btn-primary btn-fill">Guardar</button>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <script>
        $(document).ready(function () {
            $("#bt_grabar").prop("disabled", true);
            $('#btn_gr').click(function () {
                $('#new-lote').modal('show');
                $('#formdetalleventa').trigger("reset");
            });
            $('#bt_add').click(function () {
                agregar();
                sumaTotal();
                setValue();
                $("#articulo").val(" ");
                $("#precio").val(" ");
                $("#cantidad").val(" ");
                $("#lotes").val(" ");
                $("#form_stock").val(1);
                $("#est_existe").val(2);
                $("#lotes").prop("disabled", true);
                $("#bt_add").prop("disabled", true);
                avilitar_guardado();
                var fechform = $("#fecha_nota").val();
                validacion_form_fecha(fechform);
            });
            $('#bt_edit').click(function () {
                var fila = $("#variableModificando").val();
                var articulo=$("#edit_articulo").val();
                var precio=$("#edit_precio").val();
                var cantidad=$("#edit_cantidads").val();
                var lotes=$("#edit_lote").val();
                var subtotal= precio * cantidad;
                document.getElementById("tabla").rows[fila].cells[0].innerHTML = articulo;
                document.getElementById("tabla").rows[fila].cells[1].innerHTML = precio;
                document.getElementById("tabla").rows[fila].cells[2].innerHTML = cantidad;
                document.getElementById("tabla").rows[fila].cells[3].innerHTML = subtotal;
                document.getElementById("tabla").rows[fila].cells[5].innerHTML = lotes;
                sumaTotal();
                $('#edit-detalle').modal('hide');
            });
            $('#lotes').click(function(){
                var lote = $(this).val();
                $.ajax({
                    url:"{{ route('precio.fetch') }}",
                    method:'GET',
                    data:{query:lote },
                    dataType:'json',
                    success:function(data)
                    {
                        $("#precio").val(data);
                        $('#cantidad').val("");
                        existente();
                        validar_vacios();
                    }
                });
            });
            $('#edit_lote').click(function(){
                var lote = $(this).val();
                var cantform = $("#edit_cantidads").val();
                $.ajax({
                    url:"{{ route('precioEdit.fetch') }}",
                    method:'GET',
                    data:{query:lote },
                    dataType:'json',
                    success:function(data)
                    {
                        $("#edit_precio").val(data);
                        existenteEDIT();
                        validar_vacios_edit();
                        validacion_edit_cantidad(cantform , lote);
                    }
                });
            });

            $("#precio").keyup(function () {
                validar_vacios();
            });
            $("#edit_precio").keyup(function () {
                validar_vacios_edit();
            });
            $("#edit_cantidads").keyup(function () {
                var loteform =$("#edit_lote").val();
                var cantform = $("#edit_cantidads").val();
                if (cantform == 0){
                    $("#form_stock").val(1);
                    validar_vacios_edit();
                }else {
                    validacion_edit_cantidad(cantform , loteform);
                }
            });
            $('#Glosa_nota').keyup(function () {
                avilitar_guardado();
            });
        });

        var cont=0;

        function agregar() {
            cont++;
            var articulo=$("#articulo").val();
            var precio=$("#precio").val();
            var cantidad=$("#cantidad").val();
            var lotes=$("#lotes").val();
            var TotalSum= (precio * cantidad);
            var fila='<tr class="selected" id="fila'+cont+'"><td class="Articulo">'+articulo+'</td><td class="Precio">'+precio+'</td><td class="Cantidad">'+cantidad+'</td><td class="TotalSum">'+TotalSum+'</td><td class="Boton">' +
                '<button type="button" value="Remove" onclick="removeRow(this)" class="btn btn-danger btn-fill fa fa-trash-o" style="color: #fff;padding-left: 6px;width: 30px;height: 31px;padding-bottom: 8px;padding-top: 4px;" data-toggle="tooltip" title="Eliminar"></button>' +
                '<button type="button" onclick="modificando(this)" class="btn btn-primary btn-fill fa fa-pencil" style="color: #fff;padding-left: 6px;width: 30px;height: 31px;padding-bottom: 8px;padding-top: 4px;" data-toggle="tooltip" title="Editar"></button>\n</td><td class="TotalSum" style="display: none">'+lotes+'</td></tr>';
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
                debe_sum[i]= parseFloat(document.getElementById("tabla").rows[columna].cells[3].innerHTML);
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
            var val_articulo = $('#articulo').val();
            var val_lotes=$("#lotes").val();
            var articulos;
            var lotes;
            var ct = 0;
            var columna = 1;
            var a = columnas - 1;
            var i;
            for (i = 0; i < a; i++) {
                articulos =document.getElementById("tabla").rows[columna].cells[0].innerHTML;
                lotes =document.getElementById("tabla").rows[columna].cells[5].innerHTML;
                if (articulos == val_articulo && lotes == val_lotes) {
                    ct++;
                }else {
                    columna++;
                }
            }

            if (ct != 0){
                $("#est_existe").val("2");
                $('#divLote').html("Solo se puede agregar un articulo por lote");
            }else {
                $('#divLote').html(" ");
                $("#est_existe").val("1");
            }
        }
        //Editar Existencia
        function existenteEDIT() {
            var columnas=document.getElementById("tabla").rows.length;
            var val_articulo = $('#edit_articulo').val();
            var val_lotes=$("#edit_lote").val();
            var val_id_lotes=$("#variableIG").val();
            var articulos;
            var lotes;
            var ct = 0;
            var columna = 1;
            var a = columnas - 1;
            var i;
            for (i = 0; i < a; i++) {
                articulos =document.getElementById("tabla").rows[columna].cells[0].innerHTML;
                lotes =document.getElementById("tabla").rows[columna].cells[5].innerHTML;
                if (articulos == val_articulo && lotes == val_lotes && lotes != val_id_lotes) {
                    ct++;
                }else {
                    columna++;
                }
            }

            if (ct != 0){
                $("#est_existes").val("2");
                $('#divLoteError').html("Este Lote ya fue ingresado");
            }else {
                $('#divLoteError').html(" ");
                $("#est_existes").val("1");
            }
        }
        function setValue() {
            var articulos=new Array();
            var precios=new Array();
            var cantidades=new Array();
            var lotes=new Array();
            var col=document.getElementById("tabla").rows.length;
            var columna = 1;
            var a = col - 1;
            var i;
            for (i = 0; i < a; i++) {
                articulos[i]=document.getElementById("tabla").rows[columna].cells[0].innerHTML;
                precios[i]=document.getElementById("tabla").rows[columna].cells[1].innerHTML;
                cantidades[i]=parseInt(document.getElementById("tabla").rows[columna].cells[2].innerHTML);
                lotes[i]=document.getElementById("tabla").rows[columna].cells[5].innerHTML;
                columna++;
            }
            var articulo = articulos.toString();
            var precio = precios.toString();
            var cantidad = cantidades.toString();
            var lote = lotes.toString();

            $("#det_articulo").val(articulo);
            $("#det_precio").val(precio);
            $("#det_cantidad").val(cantidad);
            $("#det_lote").val(lote);
            $("#det_cant").val(a);
        }
        //Buscar Lote Nuevo
        function buscar_lote(id) {
            var _token = $('input[name="_token"]').val();
            var query = id;
            $.ajax({
                url:"{{ route('Lote.fetch') }}",
                method:"POST",
                data:{query:query, _token:_token},
                success:function(data){
                    $("#lotes").prop("disabled", false);
                    $('#lotes').html(data);
                    existente();
                    validar_vacios();
                }
            });
        }

        //Buscar Lote Edit
        function buscar_lote_edit(id) {
            var _token = $('input[name="_token"]').val();
            var query = id;
            $.ajax({
                url:"{{ route('Lote.Edit') }}",
                method:"POST",
                data:{query:query, _token:_token},
                success:function(data){
                    $("#edit_lote").prop("disabled", false);
                    $('#edit_lote').html(data);
                    existente();
                    validar_vacios();
                }
            });
        }

        function modificando(numero){
            var fila = numero.parentNode.parentNode.rowIndex;
            var articulos=document.getElementById("tabla").rows[fila].cells[0].innerHTML;
            var precios=document.getElementById("tabla").rows[fila].cells[1].innerHTML;
            var cantidades=document.getElementById("tabla").rows[fila].cells[2].innerHTML;
            var lotes=document.getElementById("tabla").rows[fila].cells[5].innerHTML;

            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('LoteEdit.fetch') }}",
                method:"POST",
                data:{query:lotes, _token:_token},
                success:function(data){
                    separador = ",",
                        arreglo = data.split(separador);
                    $('#edit-detalle').modal('show');
                    $('#edit_lote').html(data);
                    $("#edit_articulo").val(articulos);
                    $("#variableIG").val(lotes);
                    $("#edit_precio").val(precios);
                    $("#edit_cantidads").val(cantidades);
                    $("#variableModificando").val(fila);
                }
            });
            //Crear el insertado a los respectivos inputs
        }
        function removeRow(oButton) {
            var empTab = document.getElementById('tabla');
            empTab.deleteRow(oButton.parentNode.parentNode.rowIndex);
            sumaTotal();
            desblock();
        }

        function avilitar_guardado() {
            var glosa_nota = $('#Glosa_nota').val();
            var fecha_nota = $('#fecha_nota').val();
            var cantidad_gl = $('#det_cant').val();
            var est = $('#val_periodo').val();
            if ( glosa_nota == 0 || cantidad_gl == 0 || est == 2 || fecha_nota == 0){
                $("#bt_grabar").prop("disabled", true);
            } else {
                $("#bt_grabar").prop("disabled", false);
            }
        }

        function validar_vacios() {
            var existe=$("#est_existe").val();
            var precio=$("#precio").val();
            var lotes=$("#lotes").val();
            var cantidad=$("#cantidad").val();
            var stock = $("#form_stock").val();
            $("#bt_add").prop("disabled", true);
            if (existe == 2 || stock == 1 || precio == 0 || lotes == 0 || cantidad == 0){
                $("#bt_add").prop("disabled", true);
            }else {
                $("#bt_add").prop("disabled", false);
            }
        }

        function validar_vacios_edit() {
            var existe=$("#est_existes").val();
            var stock=$("#stock_edit").val();
            var lote=$("#edit_lote").val();
            var precio=$("#edit_precio").val();
            var cantidad=$("#edit_cantidads").val();
            if (lote == 0 || precio == 0 || stock == 1 || existe == 2 || stock == 0 || cantidad == 0){
                $("#bt_edit").prop("disabled", true);
            }else {
                $("#bt_edit").prop("disabled", false);
            }
        }

        function validacion_form_fecha(e)
        {
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
        //verificar cantidad
        function validacion_edit_cantidad(cantform , loteform)
        {
            $.ajax({
                url:"{{ route('cantidadEdit.val') }}",
                method:'GET',
                data:{query:cantform , lote:loteform },
                dataType:'json',
                success:function(data)
                {
                    $("#stock_edit").val(data);
                    validar_vacios_edit();
                }
            });
        }
        function validacion_form_cantidad(cantform , loteform)
        {
            $.ajax({
                url:"{{ route('cantidad.val') }}",
                method:'GET',
                data:{query:cantform , lote:loteform },
                dataType:'json',
                success:function(data)
                {
                    $("#form_stock").val(data);
                    validar_vacios();
                }
            });
        }


        $("#cantidad").keyup(function () {
            var loteform =$("#lotes").val();
            var cantform = $("#cantidad").val();
            if (cantform == 0){
                $("#form_stock").val(1);
                validar_vacios();
            }else {
                validacion_form_cantidad(cantform , loteform);
            }
        });




        {{--function buscar_lotes(id) {--}}
        {{--var _token = $('input[name="_token"]').val();--}}
        {{--var query = id;--}}
        {{--$.ajax({--}}
        {{--url:"{{ route('Lote.fetch') }}",--}}
        {{--method:"POST",--}}
        {{--data:{query:query, _token:_token},--}}
        {{--success:function(data){--}}
        {{--$('#edit_lote').html(data);--}}
        {{--// $('#precio').val(data);--}}
        {{--}--}}
        {{--});--}}
        {{--}--}}

    </script>
@endsection