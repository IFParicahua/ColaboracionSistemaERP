@extends('menus')
@section('content')
    <?php
    //    $resul = DB::table('notas')->where([['PkEmpresa','=',Session('emp-id')],['Tipo','=',1]])->orderby('id','DESC')->take(1)->value('NroNota');
    //    $tipos = DB::table('concepto')->get();

    $series = DB::table('notas')->where([['PkEmpresa', '=', Session('emp-id')],['id', '=',Session('nota-lote-id')]])->orderby('id','DESC')->take(1)->value('NroNota');

    $estados = DB::table('concepto')
        ->join('notas', 'concepto.id', '=', 'notas.Estado')
        ->where([['notas.PkEmpresa','=',Session('emp-id')],['notas.id','=',Session('nota-lote-id')]])
        ->orderby('concepto.id','DESC')->take(1)
        ->value('concepto.Nombres');
    ?>
    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <div class="panel-body">
                <div class="card-body" style="padding-top: 0px">
                    <form action="{{url('nota-lote/anular')}}" method=post name=test onSubmit=setValue()>
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-3 pl-1">
                                <h4 class="card-title">Nota de Compra</h4>
                            </div>
                            <div class="col-md-2 pl-0">
                                <button type="submit" class="btn btn-danger btn-fill fa fa-trash-o" id="bt_grabar" name="bt_grabar" ></button>
                                <button type="button" class="btn btn-primary btn-fill fa fa-print" data-toggle="tooltip" title="Imprimir" id="print" name="print"></button>

                                <script>
                                    $(document).ready(function(){
                                        $('#print').click(function () {
                                            var notas = $('#id_nota').val();
                                            javascript:window.open('http://localhost:8000/Reportes/Compra/index.php?notas='+notas,'','width=900,height=500,left=50,top=50');
                                        })
                                    });
                                </script>

                            </div>
                            <input id="det_articulo" name="det_articulo" type="hidden">
                            <input id="det_precio" name="det_precio" type="hidden">
                            <input id="det_cantidad" name="det_cantidad" type="hidden">
                            <input id="det_total" name="det_total" type="hidden">
                            <input id="det_vencimiento" name="det_vencimiento" type="hidden">
                            <input id="det_cant" name="det_cant" type="hidden">
                            <div class="col-md-5">
                                <p class="blockquote  text-right" >
                                    @if($estados == "Anulado")
                                        {{$estados}}
                                        <script>
                                            $("#bt_grabar").prop("disabled", true);
                                        </script>
                                    @endif

                                </p>
                            </div>
                            <div class="col-md-2">
                                <p class="blockquote  text-right" >Serie: {{$series}}</p>
                            </div>
                        </div>
                        @forelse($notas as $nota)
                            <div class="row">
                                <div class="col-md-2 pr-1" style="">
                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <input type="date" id="fecha_nota" name="fecha_nota" class="form-control" style="padding-left: 1px;padding-right: 0px" value="{{$nota->Fecha}}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-5 pl-1">
                                    <div class="form-group">
                                        <label>Detalle</label>
                                        <input type="hidden" id="id_nota" name="id_nota" class="form-control" placeholder="id_nota" value="{{$nota->id}}">
                                        <input type="text" id="Glosa_nota" name="Glosa_nota" class="form-control" placeholder="Glosa" value="{{$nota->Detalle}}" disabled>
                                    </div>
                                </div>

                            </div>
                        @empty
                            <div class="alert alert-danger" role="alert">No existen Lotes</div>
                        @endforelse
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
                    </style>
                    <div class="card-body table-full-width table-responsive" style="padding-bottom: 0px;">
                        <table id="tabla" class="table table-hover table-striped">
                            <thead>
                            <th style="width: 300px">Articulo</th>
                            <th style="width: 150px">Precio</th>
                            <th style="width: 150px">Cantidad</th>
                            <th style="width: 150px">Subtotal</th>
                            <th style="width: 150px">Vencimiento</th>
                            </thead>
                            <tbody>
                            @forelse($lotes as $lote)
                                <tr class="data-row">
                                    <td class="Articulo">{{$lote->Nombre}}</td>
                                    <td class="Precio">{{$lote->PrecioCompra}}</td>
                                    <td class="Cantidad">{{$lote->Cantidad}}</td>
                                    <td class="TotalSum">{{$lote->SubTotal}}</td>
                                    <td class="Vencimiento">{{$lote->FechaVencimiento}}</td>

                                </tr>
                            @empty
                                <div class="alert alert-danger" role="alert">No existen Lotes</div>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <table>
                                <tbody style="height: 42px;width: 800px;">
                                <tr>
                                    <td class="text-right" style="width: 620px;padding-right: 10px;font-family: 'Arial Black'; font-size: 17px">Total</td>
                                    <td style="width: 150px;"><input class="form-control" id="in_total" name="in_total" type="text" disabled></td>
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
    <script>
        $( document ).ready(function() {
            sumaTotal();
            setValue();
        });
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
                vencimientos[i]=document.getElementById("tabla").rows[columna].cells[5].innerHTML;
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
    </script>
@endsection