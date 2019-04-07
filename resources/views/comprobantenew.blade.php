@extends('menus')
@section('content')
    <?php
    $tipos=  DB::table('concepto')->where('Tipo', '=','Comprobante')->get();
    $moneda = DB::select('SELECT  t1.Cambio, (t2.Nombre)AS principal, (t3.Nombre)AS alternativa, (t2.id)AS idprincipal,(t3.id)AS idalternativa FROM monedaempresa t1 INNER JOIN moneda t2 ON  t1.Principal=t2.id left JOIN moneda t3 ON  t1.Alternativa=t3.id WHERE t1.PkEmpresa='.Session('emp-id').' AND t1.Activo = 1;');
    $resul = DB::table('comprobante')->where('PkEmpresa', '=', Session('emp-id'))->orderby('id_comprobante','DESC')->take(1)->value('Serie');
    $gestiones=DB::table('gestion')->where([['PkEmpresa', '=', Session('emp-id')],['Estado','=',1]])->get();
    $gFechaInicio = DB::table('gestion')->where([['PkEmpresa', '=', Session('emp-id')],['Estado','=','1']])->value('FechaInicio');
    $gFechaFin = DB::table('gestion')->where([['PkEmpresa', '=', Session('emp-id')],['Estado','=','1']])->value('FechaFin');
    ?>

    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <div class="panel-body">
                <div class="card-body" style="padding-top: 0px;">
                    <form action="{{url('comprobante/create')}}" method=post name=test onSubmit=setValue()>
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-3 pl-1">
                                <h4 class="modal-title">Nuevo Comprobante</h4>
                            </div>
                            <div class="col-md-2 pl-0">
                                <button class="btn btn-primary btn-fill fa fa-save" id="bt_grabar" name="bt_grabar" type="submit" data-toggle="tooltip" title="Grabar Comprobante"></button>
                                <button type="button" class="btn btn-primary btn-fill fa fa-plus" id="bt_com" data-target="#new-empresa" data-toggle="tooltip" title="Agregar detalles"></button>
                            </div>
                            <div class="col-md-7">
                                <p class="blockquote blockquote-primary text-right" >Serie:{{$resul+1}}</p>
                            </div>
                        </div>

                        @foreach($gestiones as $gestion)
                            <input id="fechinicio" name="det_nuemero" type="hidden" value="{{$gestion->FechaInicio}}">
                            <input id="fechfin" name="det_cuenta" type="hidden" value="{{$gestion->FechaFin}}">
                        @endforeach
                        <input id="det_nuemero" name="det_nuemero" type="hidden">
                        <input id="det_cuenta" name="det_cuenta" type="hidden">
                        <input id="det_glosa" name="det_glosa" type="hidden">
                        <input id="det_debe" name="det_debe" type="hidden">
                        <input id="det_haber" name="det_haber" type="hidden">
                        <input id="det_cant" name="det_cant" type="hidden">
                        <div class="row">
                            <div class="col-md-2 pr-1" style="">
                                <div class="form-group">
                                    <label>Fecha</label>
                                    <input type="date" id="fecha" name="fecha" class="form-control" onchange="ejemplo(event)">
                                    <script>
                                        function ejemplo(e) {
                                            var fecha = e.target.value;
                                            var tipoform = "apertura";
                                            $.ajax({
                                                url:"{{ route('form_date.validation') }}",
                                                method:'GET',
                                                data:{query:fecha, tipo:tipoform },
                                                dataType:'json',
                                                success:function(data)
                                                {
                                                    if(data == 1){
                                                        $('#mstipo').html("Ya existe un comprobante de apertura");
                                                        // $('#mstipo').fadeOut(1000);
                                                        $("#val").val(1);
                                                        desblock();
                                                    }else {
                                                        if (data == 2){
                                                            $('#msfecha').html("La fecha no esta dentro de un periodo activo");
                                                            // $('#msfecha').fadeOut(1000);
                                                            $("#val").val(2);
                                                            desblock();
                                                        }else {
                                                            if (data == 0 ){
                                                                $('#mstipo').html("");
                                                                $('#msfecha').html("");
                                                                $("#val").val(0);
                                                                desblock();
                                                            }else {
                                                                $('#msfecha').html("");
                                                                $('#mstipo').html("");
                                                                $("#val").val(3);
                                                                desblock();
                                                            }
                                                        }
                                                    }
                                                }
                                            });
                                        }
                                    </script>
                                    <div id="contenFecha">
                                        <input type="hidden" id="val" name="val" value="4" disabled>
                                    </div>
                                    <script>
                                        window.onload = function(){
                                            var fecha = new  Date();
                                            var mes = fecha.getMonth()+1;
                                            var dia =fecha.getDate();
                                            var ano =fecha.getFullYear();
                                            if(dia<10)
                                                dia='0'+dia;
                                            if(mes<10)
                                                mes='0'+mes;
                                            document.getElementById('fecha').value=ano+"-"+mes+"-"+dia;
                                        };
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-2 px-1">
                                <div class="form-group">
                                    <label>Tipo de comprobante</label>
                                    <select class="form-control" id="tipocomprobante" name="tipocomprobante" value="Por">
                                        @foreach($tipos as $tipo)
                                            <option value="{{$tipo->Nombres}}">{{$tipo->Nombres}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 px-1">
                                <div class="form-group">
                                    <label>Moneda</label>
                                    <select class="form-control" id="moneda" name="moneda" value="Por">
                                        @foreach($moneda as $monedas)
                                            <option value="{{$monedas->idprincipal}}">{{$monedas->principal}}</option>
                                            <option value="{{$monedas->idalternativa}}">{{$monedas->alternativa}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 pl-1">
                                <div class="form-group">
                                    <label>TC</label>
                                    @foreach($moneda as $monedas)
                                        <input type="text" id="TC" name="TC" class="form-control" placeholder="Tipo de Cambio" value="{{$monedas->Cambio}}" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"  >
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label>Glosa</label>
                                    <input type="text" id="Glosa_comprobante" name="Glosa_comprobante" class="form-control" placeholder="Glosa" onkeydown="glosaval()">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group" id="msfecha" style="font-size: smaller;" {{ $errors->has('fecha') ? ' has-error' : '' }}>
                                    @if ($errors->has('fecha'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('fecha') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" id="mstipo" style="font-size: smaller;" {{ $errors->has('tipocomprobante') ? ' has-error' : '' }}>
                                    @if ($errors->has('tipocomprobante'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('tipocomprobante') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" >
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" id="msCn" style="font-size: smaller;" {{ $errors->has('det_cuenta') ? ' has-error' : '' }}>
                                    @if ($errors->has('det_cuenta'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('det_cuenta') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group" id="msglosa" {{ $errors->has('Glosa_comprobante') ? ' has-error' : '' }}>
                                    @if ($errors->has('Glosa_comprobante'))
                                        <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('Glosa_comprobante') }}</strong>
                                    </span>
                                    @endif
                                </div>
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
                        .Cuenta{
                            width: 250px;
                        }
                        .Glosa{
                            width: 334px;
                        }
                        .Debe{
                            width: 100px;
                        }
                        .Haber{
                            width: 100px;
                        }
                        .Boton{
                            width: 80px;
                        }
                    </style>

                    <div class="card-body table-full-width table-responsive">
                        <table id="tabla" class="table table-hover table-striped">
                            <thead>
                            <th class="digito"></th>
                            <th class="Cuenta">Cuenta</th>
                            <th class="Glosa">Glosa</th>
                            <th class="Debe">Debe</th>
                            <th class="Haber">Haber</th>
                            <th class="Boton"></th>
                            </thead>
                            <tbody>
                            <tr class="data-row">

                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table>
                                <tbody style="height: 42px">
                                <tr>
                                    <td class="text-right" style="width: 664px;padding-right: 10px;font-family: 'Arial Black'; font-size: 17px">Total</td>
                                    <td style="width: 100px;"><input class="form-control" id="in_debe" name="in_debe" type="number"></td>
                                    <td style="width: 100px;"><input class="form-control" id="in_haber" name="in_haber" type="number"></td>
                                    <td style="width: 80px"></td>
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
    <!-- Modal New Comprobante -->
    <div id="new-empresa" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <h4 class="modal-title" >Nuevo Detalle</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form" id="formcomprobante" name="formcomprobante" onsubmit="return validacion()">
                        {!! csrf_field() !!}
                        <div class="panel-body">
                            <input type="hidden" name="estado_select" id="estado_select" class="form-control" value=" "/>

                            <div class="row" style="height: 110px;">
                                <div class="form-group col-md-3 pl-1" {{ $errors->has('country_name') ? ' has-error' : '' }} >
                                    <label for="country_name" class="control-label">Cuenta:</label>
                                    <input type="text" name="country_name" id="country_name" class="form-control" placeholder="" />
                                    <div id="countryList"></div>
                                    <div id="duplicado"></div>
                                    {{ csrf_field() }}
                                    <script>
                                        $(document).ready(function(){

                                            $('#country_name').keyup(function(){
                                                $('#estado_select').val(" ");
                                                validar_campos_vacios();
                                                var query = $(this).val();
                                                if(query != '')
                                                {
                                                    var _token = $('input[name="_token"]').val();
                                                    $.ajax({
                                                        url:"{{ route('autocomplete.fetch') }}",
                                                        method:"POST",
                                                        data:{query:query, _token:_token},
                                                        success:function(data){
                                                            $('#countryList').fadeIn();
                                                            $('#countryList').html(data);
                                                        }
                                                    });
                                                    existente();
                                                }
                                            });
                                            $(document).on('click', 'li', function(){
                                                $('#country_name').val($(this).text());
                                                $('#countryList').fadeOut();
                                                $('#estado_select').val("1");
                                                existente();
                                                validar_campos_vacios();
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="form-group col-md-5 pl-1">
                                    <label for="Glosa" class="control-label">Glosa:</label>
                                    <input type="text" class="form-control" id="Glosa" name="Glosa" value="{{old('Glosa') }}" onkeyup="validar_campos_vacios()">
                                </div>
                                <div class="form-group col-md-2 pl-1" {{ $errors->has('Debe') ? ' has-error' : '' }}>
                                    <label for="Debe" class="control-label">Debe:</label>
                                    <input type="number" class="form-control" id="Debe" name="Debe" value="0" onkeyup="validacion(1)">
                                    @if ($errors->has('Debe'))
                                        <span id="alerta"  class="help-block">
                                        <label class="text-danger">{{ $errors->first('Debe') }}</label>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group col-md-2 pl-1 " {{ $errors->has('Haber') ? ' has-error' : '' }}>
                                    <label for="Haber" class="control-label">Haber:</label>
                                    <input type="number"  class="form-control" id="Haber" name="Haber" maxlength="8" value="0" onkeyup="validacion(2)">
                                    @if ($errors->has('Haber'))
                                        <span class="help-block">
                                        <label class="text-danger">{{ $errors->first('Haber') }}</label>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cerrar</button>
                            <button type="button" id="bt_add" class="btn btn-primary btn-fill">Guardar</button>
                        </div>

                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <!-- Modal Edit Comprobante -->
    <div id="edit-comprobante" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <h4 class="modal-title" >Editar Detalle</h4>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form" id="editcomprobante" name="editcomprobante" onsubmit="return validacion()">
                        {!! csrf_field() !!}
                        <div class="panel-body">

                            <div class="row" style="height: 110px;">
                                <div class="form-group col-md-3 pl-1" {{ $errors->has('country_name') ? ' has-error' : '' }} >
                                    <label for="country_name" class="control-label">Cuenta:</label>
                                    <input type="text" id="variableModificando" hidden="true"  value="">
                                    <input type="text" name="cuenta_comprobante" id="cuenta_comprobante" class="form-control" placeholder="" />
                                    <div id="countryListedit">
                                    </div>
                                    {{ csrf_field() }}
                                    <script>
                                        $(document).ready(function(){

                                            $('#cuenta_comprobante').keyup(function(){
                                                $('#estado_select_edit').val(" ");
                                                validar_campos_vacios_edit();
                                                var query = $(this).val();
                                                if(query != '')
                                                {
                                                    var _token = $('input[name="_token"]').val();
                                                    $.ajax({
                                                        url:"{{ route('autocomplete.fetch') }}",
                                                        method:"POST",
                                                        data:{query:query, _token:_token},
                                                        success:function(data){
                                                            $('#countryListedit').fadeIn();
                                                            $('#countryListedit').html(data);
                                                        }
                                                    });
                                                }
                                            });
                                            $(document).on('click', 'li', function(){
                                                $('#cuenta_comprobante').val($(this).text());
                                                $('#countryListedit').fadeOut();
                                                $('#estado_select_edit').val("1");
                                                validar_campos_vacios_edit();
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="form-group col-md-5 pl-1">
                                    <label for="Glosaedit" class="control-label">Glosa:</label>
                                    <input type="text" class="form-control" id="Glosaedit" name="Glosaedit" >
                                </div>
                                <div class="form-group col-md-2 pl-1" >
                                    <label for="Debeedit" class="control-label">Debe:</label>
                                    <input type="number" class="form-control" id="Debeedit" name="Debeedit" onkeyup="validacionedit(1)">
                                </div>
                                <div class="form-group col-md-2 pl-1 ">
                                    <label for="Haberedit" class="control-label">Haber:</label>
                                    <input type="number"  class="form-control" id="Haberedit" name="Haberedit" maxlength="8" value="0" onkeyup="validacionedit(2)">
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
    <div id="eliminar-detalle" class="modal fade col-lg-12" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header"></div>
                <input id="delFila" name="delFila" type="hidden">
                <div class="modal-body">�Estas seguro de que quiere borrar este detalle?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="bt_delete" class="btn btn-primary btn-fill">Aceptar</button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div>
    <script>
        $(document).ready(function () {
            //Validacion de fecha

            $("#in_debe").prop("disabled", true);
            $("#in_haber").prop("disabled", true);
            $("#bt_grabar").prop("disabled", false);
            $("#bt_com").prop("disabled", true);
            $('#bt_add').click(function () {
                $("#country_name").prop("disabled", false);
                agregar();
                setValue();
                $("#Haber").prop("disabled", false);
                $("#Debe").prop("disabled", false);
                sumaTotal();
                desblock();
            });

            $('#bt_com').click(function () {
                $("#bt_add").prop("disabled", true);
                var TextoGlosa=document.getElementById("Glosa_comprobante").value;
                var GlosaDetalle=document.getElementById("Glosa").value;
                if (GlosaDetalle <= 0){
                    $('#Glosa').val(TextoGlosa);
                } else {
                    $('#Glosa').oldValue;
                }
                $('#new-empresa').modal('show');
                $('#country_name').val("");
                $("#Haber").prop("disabled", false);
                $("#Debe").prop("disabled", false);
                $("#Haber").val(" ");
                $("#Debe").val(" ");
            });

            $('#bt_edit').click(function () {
                var fila = $("#variableModificando").val();
                var cuenta = $("#cuenta_comprobante").val();
                var glosa = $("#Glosaedit").val();
                var debe = $("#Debeedit").val();
                var haber = $("#Haberedit").val();
                document.getElementById("tabla").rows[fila].cells[1].innerHTML = cuenta;
                document.getElementById("tabla").rows[fila].cells[2].innerHTML = glosa;
                document.getElementById("tabla").rows[fila].cells[3].innerHTML = debe;
                document.getElementById("tabla").rows[fila].cells[4].innerHTML = haber;
                sumaTotal();
                $('#edit-comprobante').modal('hide');
            });

            $("#bt_delete").click(function () {
                var com = $("#delFila").val();
                var empTab = document.getElementById('tabla');
                empTab.deleteRow(com);
                sumaTotal();
                desblock();
                $('#eliminar-detalle').modal('hide');
            });

            validacion_form_fecha();
        });

        //Funcion para validar de Fecha
        function validacion_form_fecha(fechform = '', tipoform = '')
        {
            $.ajax({
                url:"{{ route('form_date.validation') }}",
                method:'GET',
                data:{query:fechform, tipo:tipoform },
                dataType:'json',
                success:function(data)
                {
                    if(data == 1){
                        $('#mstipo').html("Ya existe un comprobante de apertura");
                        // $('#mstipo').fadeOut(1000);
                        $("#val").val(1);
                        desblock();
                    }else {
                        if (data == 2){
                            $('#msfecha').html("La fecha no esta dentro de un periodo activo");
                            // $('#msfecha').fadeOut(1000);
                            $("#val").val(2);
                            desblock();
                        }else {
                            if (data == 0 ){
                                $('#mstipo').html("");
                                $('#msfecha').html("");
                                $("#val").val(0);
                                desblock();
                            }else {
                                $('#msfecha').html("");
                                $('#mstipo').html("");
                                $("#val").val(3);
                                desblock();
                            }
                        }
                    }
                }
            });
        }
        $(document).on('keyup', '#fecha', function(){
            var fechform = $("#fecha").val();
            var tipoform = $("#tipocomprobante").val();
            validacion_form_fecha(fechform , tipoform);
        });

        $(document).on('click', '#tipocomprobante', function(){
            var fechform = $("#fecha").val();
            var tipoform = $("#tipocomprobante").val();

            if(tipoform != 0){
                validacion_form_fecha(fechform , tipoform);
            }
        });

        //Fin Funcion para validar de Fecha

        function validacion(id) {
            validar_campos_vacios();
            var id= id;
            var debe=document.getElementById("Debe").value;
            var haber=document.getElementById("Haber").value;

            if (id == 1){
                if (debe == 0){
                    $("#Haber").prop("disabled", false);
                    $("#Haber").val(" ");
                }else {
                    $("#Haber").prop("disabled", true);
                    $("#Haber").val(0);
                }
            }else {
                if (haber == 0){
                    $("#Debe").prop("disabled", false);
                    $("#Debe").val(" ");
                }else {
                    $("#Debe").attr('disabled', 'disabled');
                    $("#Debe").val(0);
                }

            }
        }

        function validacionedit(id) {
            validar_campos_vacios_edit();
            var id= id;
            var debe=document.getElementById("Debeedit").value;
            var haber=document.getElementById("Haberedit").value;

            if (id == 1){
                if (debe == 0){
                    $("#Haberedit").prop("disabled", false);
                    $("#Haberedit").val(" ");
                }else {
                    $("#Haberedit").prop("disabled", true);
                    $("#Haberedit").val(0);
                }
            }else {
                if (haber == 0){
                    $("#Debeedit").prop("disabled", false);
                    $("#Debeedit").val(" ");
                }else {
                    $("#Debeedit").attr('disabled', 'disabled');
                    $("#Debeedit").val(0);
                }

            }
        }
        function setValue()
        {
            var nuemeros=new Array();
            var cuentas=new Array();
            var glosas=new Array();
            var debes=new Array();
            var haberes=new Array();
            var col=document.getElementById("tabla").rows.length;
            var columna = 2;
            var a = col - 2;
            var i;
            for (i = 0; i < a; i++) {
                nuemeros[i]=document.getElementById("tabla").rows[columna].cells[0].innerHTML;
                cuentas[i]=document.getElementById("tabla").rows[columna].cells[1].innerHTML;
                glosas[i]=document.getElementById("tabla").rows[columna].cells[2].innerHTML;
                debes[i]=document.getElementById("tabla").rows[columna].cells[3].innerHTML;
                haberes[i]=document.getElementById("tabla").rows[columna].cells[4].innerHTML;
                columna++;
            }
            var nuemero = nuemeros.toString();
            var cuenta = cuentas.toString();
            var glosa = glosas.toString();
            var debe = debes.toString();
            var haber = haberes.toString();

            $("#det_nuemero").val(nuemero);
            $("#det_cuenta").val(cuenta);
            $("#det_glosa").val(glosa);
            $("#det_debe").val(debe);
            $("#det_haber").val(haber);
            $("#det_cant").val(a);
        }

        function sumaTotal() {
            var col=document.getElementById("tabla").rows.length;
            var columna = 2;
            var a = col - 2;
            var i;
            var debe_sum=new Array();
            var haber_sum=new Array();
            var total_debe = 0;
            var total_haber = 0;

            for (i = 0; i < a; i++) {
                debe_sum[i]= parseInt(document.getElementById("tabla").rows[columna].cells[3].innerHTML);
                total_debe = parseInt(debe_sum[i]) + parseInt(total_debe);
                haber_sum[i]= parseInt(document.getElementById("tabla").rows[columna].cells[4].innerHTML);
                total_haber = parseInt(haber_sum[i]) + parseInt(total_haber);
                columna++;
            }
            $("#in_debe").val(total_debe);
            $("#in_haber").val(total_haber);
            // $("#Haber").val("0");
            // $("#Debe").val("0");
        }
        var cont=0;
        function agregar() {
            cont++;
            var Debe=document.getElementById("Debe").value;
            var Haber=document.getElementById("Haber").value;
            var Glosa=document.getElementById("Glosa").value;
            var Cuenta=document.getElementById("country_name").value;
            var fila='<tr class="selected" id="fila'+cont+'"><td class="digito">'+cont+'</td><td class="Cuenta">'+Cuenta+'</td><td class="Glosa">'+Glosa+'</td><td class="Debe">'+Debe+'</td><td class="Haber">'+Haber+'</td><td class="Boton"><button type="button" value="Remove" onclick="removeRow(this)" class="btn btn-danger btn-fill fa fa-trash-o" data-toggle="tooltip" title="Eliminar"></button><button type="button" onclick="modificando(this)" class="btn btn-primary btn-fill fa fa-pencil" data-toggle="tooltip" title="Editar"></button></td></tr>';
            $('#tabla').append(fila);
            $("#Debe").val("");
            $('#Haber').val("");
            $('#country_name').val("");
            $("#bt_add").prop("disabled", true);
            $("#estado_select").val("");
        }

        function desblock() {
            var in_debe=document.getElementById("in_debe").value;
            var in_haber=document.getElementById("in_haber").value;
            var error = document.getElementById("val").value;
            if (in_debe == in_haber ) {
                if (in_debe != 0 & in_haber != 0 ){
                    if (error == 0){
                        $("#bt_grabar").prop("disabled", false);
                    } else {
                        $("#bt_grabar").prop("disabled", true);
                    }
                }
            }else {
                if (error != 0) {
                    $("#bt_grabar").prop("disabled", true);
                }
            }
        }

        function removeRow(oButton) {
            $('#eliminar-detalle').modal('show');
            var valor = oButton.parentNode.parentNode.rowIndex;
            $('#delFila').val(valor);

        }

        function modificando(numero){
            $("#bt_edit").prop("disabled", true);
            $('#edit-comprobante').modal('show');
            $("#Haberedit").prop("disabled", false);
            $("#Debeedit").prop("disabled", false);
            var fila = numero.parentNode.parentNode.rowIndex;
            var cuentas=document.getElementById("tabla").rows[fila].cells[1].innerHTML;
            var glosas=document.getElementById("tabla").rows[fila].cells[2].innerHTML;
            var debes=document.getElementById("tabla").rows[fila].cells[3].innerHTML;
            var haberes=document.getElementById("tabla").rows[fila].cells[4].innerHTML;
            $("#variableModificando").val(fila);
            $("#cuenta_comprobante").val(cuentas);
            $("#Glosaedit").val(glosas);
            if (debes > 0){
                $("#Debeedit").val(debes);
                $("#Haberedit").val(" ");
            } else{
                if (haberes > 0){
                    $("#Haberedit").val(haberes);
                    $("#Debeedit").val(" ");
                }
            }

            //Crear el insertado a los respectivos inputs
        }

        function existente() {
            var columnas=document.getElementById("tabla").rows.length;
            var val_cuenta = $('#country_name').val();
            var cuentas;
            var columna = 2;
            var a = columnas - 2;
            var i;
            for (i = 0; i < a; i++) {
                cuentas =document.getElementById("tabla").rows[columna].cells[1].innerHTML;
                if (cuentas == val_cuenta) {
                    toastr.error("Esta Cuenta ya existe");
                    columna= 1;
                    //Agregar esto para que el campo siempre sea 0 si ya existe la cuenta
                    $("#estado_select").val(" ");
                }else {
                    columna++;
                }
            }
        }
        function validar_campos_vacios() {
            var verificar = $("#estado_select").val();
            var glosa = $("#Glosa").val();
            var debe = $("#Debe").val();
            var haber = $("#Haber").val();

            if (verificar == 0 & glosa == 0){
                if (debe == 0 & haber == 0) {
                    $("#bt_add").prop("disabled", true);
                }
            }else{
                if (verificar == 0 || glosa == 0){
                    $("#bt_add").prop("disabled", true);
                }else {
                    if (debe == 0 & haber == 0){
                        $("#bt_add").prop("disabled", true);
                    } else {
                        if (debe > 0 & haber == 0){
                            $("#bt_add").prop("disabled", false);
                        } else {
                            $("#bt_add").prop("disabled", false);
                        }
                    }
                }
            }
        }

        function validar_campos_vacios_edit() {
            var verificar = $("#estado_select_edit").val();
            var glosa = $("#Glosaedit").val();
            var debe = $("#Debeedit").val();
            var haber = $("#Haberedit").val();

            if (verificar == 0 & glosa == 0){
                if (debe == 0 & haber == 0) {
                    $("#bt_edit").prop("disabled", true);
                }
            }else{
                if (verificar == 0 || glosa == 0){
                    $("#bt_edit").prop("disabled", true);
                }else {
                    if (debe == 0 & haber == 0){
                        $("#bt_edit").prop("disabled", true);
                    } else {
                        if (debe > 0 & haber == 0){
                            $("#bt_edit").prop("disabled", false);
                        } else {
                            $("#bt_edit").prop("disabled", false);
                        }
                    }
                }
            }
        }

        function glosaval() {
            var Glosa_comprobante=$('#Glosa_comprobante').val();
            if (Glosa_comprobante==0){
                $("#bt_com").prop("disabled", true);
            } else {
                $("#bt_com").prop("disabled", false);
            }

        }
    </script>
@endsection