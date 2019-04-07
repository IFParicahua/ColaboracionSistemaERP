@extends('menus')
@section('content')
    <?php
    $series = DB::table('notas')->where([['PkEmpresa', '=', Session('emp-id')],['id', '=',Session('nota-detalle-id')]])->orderby('id','DESC')->take(1)->value('NroNota');
    $estados = DB::table('concepto')
        ->join('notas', 'concepto.id', '=', 'notas.Estado')
        ->where([['notas.PkEmpresa','=',Session('emp-id')],['notas.id','=',Session('nota-detalle-id')]])
        ->orderby('concepto.id','DESC')->take(1)
        ->value('concepto.Nombres');
    ?>
    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <div class="panel-body">
                <div class="card-body" style="padding-top: 0px">
                    <form action="{{url('detalle-venta/anular')}}" method=post name=test onSubmit=setValue()>
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-3 pl-1">
                                <h4 class="card-title">Edit Nota de Venta</h4>
                            </div>
                            <div class="col-md-2 pl-0">
                                <button type="submit" class="btn btn-danger btn-fill fa fa-trash-o" id="bt_grabar" name="bt_grabar" ></button>
                                <button type="button" class="btn btn-primary btn-fill fa fa-print" data-toggle="tooltip" title="Imprimir" id="print" name="print"></button>

                                <script>
                                    $(document).ready(function(){
                                        $('#print').click(function () {
                                            var notas = $('#id_nota').val();
                                            javascript:window.open('http://localhost:8000/Reportes/Venta/index.php?notas='+notas,'','width=900,height=500,left=50,top=50');
                                        })
                                    });
                                </script>

                            </div>
                            <input id="det_articulo" name="det_articulo" type="hidden">
                            <input id="det_precio" name="det_precio" type="hidden">
                            <input id="det_cantidad" name="det_cantidad" type="hidden">
                            <input id="det_lote" name="det_lote" type="hidden">

                            <input id="det_total" name="det_total" type="hidden">
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
                    </style>
                    <div class="card-body table-full-width table-responsive" style="padding-bottom: 0px;">
                        <table id="tabla" class="table table-hover table-striped">
                            <thead>
                            <th style="width: 338px" class="Articulo">Articulo</th>
                            <th style="width: 175px">Precio</th>
                            <th style="width: 175px">Cantidad</th>
                            <th style="width: 175px">Subtotal</th>
                            </thead>
                            <tbody>
                            @forelse($detalles as $detalle)
                                <tr class="data-row">
                                    <td class="Articulo">{{$detalle->Nombre}}</td>
                                    <td class="Precio">{{$detalle->PrecioVenta}}</td>
                                    <td class="Cantidad">{{$detalle->Cantidad}}</td>
                                    <td class="TotalSum">{{$detalle->SubTotal}}</td>
                                    <td style="display: none">{{$detalle->id}}</td>
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

    <script>
        $(document).ready(function () {
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
                cantidades[i]=document.getElementById("tabla").rows[columna].cells[2].innerHTML;
                lotes[i]=document.getElementById("tabla").rows[columna].cells[4].innerHTML;

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
    </script>
@endsection