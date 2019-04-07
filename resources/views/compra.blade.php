@extends('menus')
@section('content')
    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 pl-1">
                        <h4 class="card-title">Compra</h4>
                    </div>
                    <div class="col-md-1">
                        <a id="btn_add" href="compranew" name="btn_add" class="btn btn-primary btn-fill fa fa-plus"></a>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Buscar datos" />
                    </div>
                </div>
            </div>
            <div class="card-body table-full-width table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <th >Fecha</th>
                    <th >Detalle</th>
                    <th >Total</th>
                    <th >Estado</th>
                    <th></th>
                    </thead>
                    <tbody>
                        @forelse($notas as $nota)
                            <tr class="data-row">
                                <td class="nombre">{{date("d/m/Y",strtotime($nota->Fecha))}}</td>
                                <td class="inicio">{{$nota->Detalle}}</td>
                                <td class="fin">{{$nota->Total}}</td>
                                <td>
                                    <a href="notacompra/{{$nota->id}}" class="btn btn-primary btn-fill fa fa-pencil"></a>
                                </td>
                            </tr>

                            @empty
                            <div class="alert alert-danger" role="alert">No existen notas</div>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{--<script>--}}
        {{--$(document).ready(function(){--}}

            {{--fetch_customer_data();--}}

            {{--function fetch_customer_data(query = '')--}}
            {{--{--}}
                {{--$.ajax({--}}
                    {{--url:"{{ route('live_search.action') }}",--}}
                    {{--method:'GET',--}}
                    {{--data:{query:query},--}}
                    {{--dataType:'json',--}}
                    {{--success:function(data)--}}
                    {{--{--}}
                        {{--$('tbody').html(data.table_data);--}}
                    {{--}--}}
                {{--})--}}
            {{--}--}}
            {{--$(document).on('keyup', '#search', function(){--}}
                {{--var query = $(this).val();--}}
                {{--fetch_customer_data(query);--}}
            {{--});--}}
        {{--});--}}
    {{--</script>--}}
@endsection
