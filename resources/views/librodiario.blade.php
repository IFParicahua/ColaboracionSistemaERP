@extends('menus')
@section('content')
    <?php
    $moneda = DB::select('SELECT  t1.Cambio, (t2.Nombre)AS principal, (t3.Nombre)AS alternativa, (t2.id)AS idprincipal,(t3.id)AS idalternativa FROM monedaempresa t1 INNER JOIN moneda t2 ON  t1.Principal=t2.id left JOIN moneda t3 ON  t1.Alternativa=t3.id WHERE t1.PkEmpresa='.Session('emp-id').' AND t1.Activo = 1;');
    ?>
    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <div class="panel-body">
                <br>
                <div>
                    <h4 class="card-title">Reporte Libro diario</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group ">
                                <label for="roll">Gestion <span class="required"></span></label>
                                <select name="gestions" class="form-control" id="gestions">
                                    @foreach($gestiones as $gestion)
                                        <option value="{{ $gestion->id}}">{{ $gestion->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function(){
                                $('#gestions').click(function(){
                                    var query = $(this).val();
                                    if(query != '')
                                    {
                                        var _token = $('input[name="_token"]').val();
                                        $.ajax({
                                            url:"{{ route('LibroDiario.fetch') }}",
                                            method:"POST",
                                            data:{query:query, _token:_token},
                                            success:function(data){
                                                $('#periodos').html(data);
                                            }
                                        });
                                    }
                                });
                            });
                        </script>
                        <div class="col-md-3">
                            <div class="form-group ">
                                <label for="roll">Periodo </label>
                                <select name="periodos" class="form-control" id="periodos">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Moneda</label>
                                <select class="form-control" id="moneda" name="moneda" >
                                    @foreach($moneda as $monedas)
                                        <option value="1">{{$monedas->principal}}</option>
                                        <option value="2">{{$monedas->alternativa}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input value="{{Auth::user()->id}}" id="user" name="user" type="hidden">
                        <div class="col-md-3" style="padding-top: 25px">
                            <div class="form-group ">
                                <button id="print" name="print" type="button" class="btn btn-primary btn-fill fa fa-print" data-toggle="tooltip" title="Imprimir Periodos" ></button>
                                {{--<button id="printgest" name="printgest" type="button" class="btn btn-default btn-fill fa fa-print" data-toggle="tooltip" title="Imprimir Gestiones" ></button>--}}

                            </div>
                            <script>
                                $(document).ready(function(){
                                    $('#print').click(function () {
                                        var moneda = $('#moneda').val();
                                        var usuario = $('#user').val();
                                        var periodo = $('#periodos').val();
                                        if(moneda == "1") {
                                            javascript:window.open('http://localhost:8000/Reportes/RepLibroDiario/indexprincipal.php?periodo='+periodo+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                                        }else{
                                            javascript:window.open('http://localhost:8000/Reportes/RepLibroDiario/indexalternativo.php?periodo='+periodo+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                                        }

                                        // javascript:window.open('http://localhost:8000/Reportes/librodiario.php?periodo='+periodo+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                                    })

                                    $('#printgest').click(function () {
                                        var moneda = $('#moneda').val();
                                        var usuario = $('#user').val();
                                        var periodo = $('#gestions').val();
                                        if(moneda == "1") {
                                            javascript:window.open('http://localhost:8000/Reportes/RepLibroDiario/indexprincipalgestion.php?periodo='+periodo+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                                        }else{
                                            javascript:window.open('http://localhost:8000/Reportes/RepLibroDiario/indexalternativogestion.php?periodo='+periodo+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                                        }

                                        // javascript:window.open('http://localhost:8000/Reportes/librodiario.php?periodo='+periodo+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                                    })
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection