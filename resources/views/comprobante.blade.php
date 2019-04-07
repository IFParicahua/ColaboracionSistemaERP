@extends('menus')
@section('content')
    <div class="col-md-12">
        <div class="card strpied-tabled-with-hover">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 pl-1">
                        <h4 class="card-title">Comprobantes</h4>
                    </div>
                    <div class="col-md-1">
                        <a id="btn_add" href="comprobantenew" name="btn_add" class="btn btn-primary btn-fill fa fa-plus"></a>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Buscar datos" />
                    </div>
                </div>
            </div>
            <div class="card-body table-full-width table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <th >SERIE</th>
                    <th >GLOSA</th>
                    <th >TIPO DE COMPROBANTE</th>
                    <th >FECHA</th>
                    <th>ESTADO</th>
                    <th></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){

            fetch_customer_data();

            function fetch_customer_data(query = '')
            {
                $.ajax({
                    url:"{{ route('live_search.action') }}",
                    method:'GET',
                    data:{query:query},
                    dataType:'json',
                    success:function(data)
                    {
                        $('tbody').html(data.table_data);
                    }
                })
            }
            $(document).on('keyup', '#search', function(){
                var query = $(this).val();
                fetch_customer_data(query);
            });
        });
    </script>
@endsection
