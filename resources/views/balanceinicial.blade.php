@extends('menus')
@section('content')
    <?php
    $moneda = DB::select('SELECT  t1.Cambio, (t2.Nombre)AS principal, (t3.Nombre)AS alternativa, (t2.id)AS idprincipal,(t3.id)AS idalternativa FROM monedaempresa t1 INNER JOIN moneda t2 ON  t1.Principal=t2.id left JOIN moneda t3 ON  t1.Alternativa=t3.id WHERE t1.PkEmpresa='.Session('emp-id').' AND t1.Activo = 1;');
    ?>
    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <h2 class="card-title">Reporte Balance Inicial
            </h2>
            <div class="panel-body">
                <input value="{{Auth::user()->id}}" id="user" name="user" type="hidden">
                <input value="{{Session("emp-id")}}" id="emp" name="emp" type="hidden">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group ">
                            <label>Gestion </label>
                            <select name="gestions" class="form-control" id="gestions">
                                @foreach($gestiones as $gestion)
                                    <option value="{{ $gestion->id}}">{{ $gestion->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{--<div class="col-md-6">--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="roll">Moneda</label>--}}
                            {{--<select class="form-control" id="moneda" name="moneda" >--}}
                                {{--@foreach($moneda as $monedas)--}}
                                    {{--<option value="2">{{$monedas->alternativa}}</option>--}}
                                    {{--<option value="1">{{$monedas->principal}}</option>--}}

                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="col-md-6">
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
                    <div class="col-md-8">
                        <div class="form-group ">
                            <button id="print" name="print" type="button" class="btn btn-primary fa fa-print btn-md" data-toggle="tooltip" title="Imprimir" ></button>
                        </div>
                        <script>
                            $(document).ready(function(){
                                $('#print').click(function () {
                                    var moneda = $('#moneda').val();
                                    var usuario = $('#user').val();
                                    var empresa = $('#emp').val();
                                    var gestion = $('#gestions').val();
                                    if(moneda == "1") {
                                        javascript:window.open('http://localhost:8000/Reportes/RepBalanceInicial/index.php?empresa='+empresa+'&gestion='+gestion+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                                    }else{
                                        javascript:window.open('http://localhost:8000/Reportes/RepBalanceInicial/indexAlt.php?empresa='+empresa+'&gestion='+gestion+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                                    }

                                    // javascript:window.open('http://localhost:8000/Reportes/balanceinicial.php?empresa='+empresa+'&gestion='+gestion+'&usuario='+usuario,'','width=900,height=500,left=50,top=50');
                                })
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection