@extends('menus')
@section('content')
    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <div class="card-header ">
                <div class="row">
                    <div class="col-md-3 pl-1">
                        <h4 class="card-title">Lotes de {{Session('articulo-nombre-lote','si no hay variable')}}</h4>
                    </div>
                    <div class="col-md-1">
                        <a class="btn btn-primary btn-fill fa fa-mail-reply" href="articulo"  style="border-radius: 100px;padding-left: 6px;width: 30px;height: 31px;padding-bottom: 8px;padding-top: 4px;color: #FFFFFF"></a>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Buscar datos" />
                    </div>
                </div>
            </div>
            <div class="card-body table-full-width table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <th >Nro</th>
                    <th >INGRESO</th>
                    <th >VENCIMIENTO</th>
                    <th >CANT. INGRESADO</th>
                    <th >STOCK</th>
                    </thead>
                    <tbody>
                    @forelse($lotes as $lote)
                        <tr class="data-row">
                            <td class="nombre">{{$lote->NroLote}}</td>
                            <td class="nombre">{{$lote->FechaIngreso}}</td>
                            <td class="nombre">{{$lote->FechaVencimiento}}</td>
                            <td class="nombre">{{$lote->Cantidad}}</td>
                            <td class="nombre">{{$lote->Stock}}</td>

                        </tr>
                    @empty
                        <div class="alert alert-danger" role="alert">No existen Notas</div>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){

        });
    </script>
@endsection
