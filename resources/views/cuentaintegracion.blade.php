@extends('menus')
@section('content')
    <style>
        #sliderLabel {
            border: 1px solid #a2a2a2;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            cursor: pointer;
            display: block;
            height: 30px;
            overflow: hidden;
            position: relative;
            width: 100px;
            display: inline-block;
        }

        #sliderLabel input {
            display: none;
        }

        #sliderLabel input:checked + #slider {
            left: 0px;
        }

        #slider {
            left: -50px;
            position: absolute;
            top: 0px;
            -webkit-transition: left .25s ease-out;
            -moz-transition: left .25s ease-out;
            -o-transition: left .25s ease-out;
            -ms-transition: left .25s ease-out;
            transition: left .25s ease-out;
        }

        #sliderOn,
        #sliderBlock,
        #sliderOff {
            display: block;
            font-family: arial, verdana, sans-serif;
            font-weight: bold;
            height: 30px;
            line-height: 30px;
            position: absolute;
            text-align: center;
            top: 0px;
        }

        #sliderOn {
            background: #3269aa;
            background: -webkit-linear-gradient(top, #3269aa 0%, #82b3f4 100%);
            background: -moz-linear-gradient(top, #3269aa 0%, #82b3f4 100%);
            background: -o-linear-gradient(top, #3269aa 0%, #82b3f4 100%);
            background: -ms-linear-gradient(top, #3269aa 0%, #82b3f4 100%);
            background: linear-gradient(top, #3269aa 0%, #82b3f4 100%);
            color: white;
            left: 0px;
            width: 54px;
        }

        #sliderBlock {
            background: #d9d9d8;
            background: -webkit-linear-gradient(top, #d9d9d8 0%, #fcfcfc 100%);
            background: -moz-linear-gradient(top, #d9d9d8 0%, #fcfcfc 100%);
            background: -o-linear-gradient(top, #d9d9d8 0%, #fcfcfc 100%);
            background: -ms-linear-gradient(top, #d9d9d8 0%, #fcfcfc 100%);
            background: linear-gradient(top, #d9d9d8 0%, #fcfcfc 100%);
            border: 1px solid #a2a2a2;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            height: 28px;
            left: 50px;
            width: 50px;
        }

        #sliderOff {
            background: #f2f3f2;
            background: -webkit-linear-gradient(top, #8b8c8b 0%, #f2f3f2 50%);
            background: -moz-linear-gradient(top, #8b8c8b 0%, #f2f3f2 50%);
            background: -o-linear-gradient(top, #8b8c8b 0%, #f2f3f2 50%);
            background: -ms-linear-gradient(top, #8b8c8b 0%, #f2f3f2 50%);
            background: linear-gradient(top, #8b8c8b 0%, #f2f3f2 50%);
            color: #8b8b8b;
            left: 96px;
            width: 54px;
        }
    </style>

    <div class="col-lg-12">
        <div >
            <div >
                <div class="row">
                    <div class="col-md-3 pl-1">
                        <h4>Integracion de Cuentas</h4>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary btn-fill fa fa-mail-reply" id="btn_edit" name="btn_edit" ></button>
                    </div>
                </div>
            </div>
            <div class="card-body table-full-width table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Caja</th>
                        <th>Credito Fiscal</th>
                        <th>Debito Fiscal</th>
                        <th>Compras</th>
                        <th>Ventas</th>
                        <th>IT</th>
                        <th>IT por pagar</th>
                        <th>Activo</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($integraciones as $integracione)
                        <tr class="data-row">
                            <td>
                                @foreach ($cuentas as $cuenta)
                                    @if ($integracione->Caja == $cuenta->id_cuenta)
                                        {{$cuenta->Nombre}}
                                    @endif
                                @endforeach
                            </td>

                            <td>
                                @foreach ($cuentas as $cuenta)
                                    @if ($integracione->CreditoFiscal == $cuenta->id_cuenta)
                                        {{$cuenta->Nombre}}
                                    @endif
                                @endforeach
                            </td>

                            <td>
                                @foreach ($cuentas as $cuenta)
                                    @if ($integracione->DebitoFiscal == $cuenta->id_cuenta)
                                        {{$cuenta->Nombre}}
                                    @endif
                                @endforeach
                            </td>

                            <td>
                                @foreach ($cuentas as $cuenta)
                                    @if ($integracione->Compras == $cuenta->id_cuenta)
                                        {{$cuenta->Nombre}}
                                    @endif
                                @endforeach
                            </td>

                            <td>
                                @foreach ($cuentas as $cuenta)
                                    @if ($integracione->Ventas == $cuenta->id_cuenta)
                                        {{$cuenta->Nombre}}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($cuentas as $cuenta)
                                    @if ($integracione->IT == $cuenta->id_cuenta)
                                        {{$cuenta->Nombre}}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($cuentas as $cuenta)
                                    @if ($integracione->ITporPagar == $cuenta->id_cuenta)
                                        {{$cuenta->Nombre}}
                                    @endif
                                @endforeach
                            </td>
                            {{--<td>--}}
                            {{--<input id="toggle-event" name="toggle-event" type="checkbox" data-toggle="toggle" value="{{$integracione->Activo}}">--}}
                            {{--</td>--}}

                            <td>
                                <label id="sliderLabel">
                                    <input id="toggle-event" name="toggle-event" type="checkbox" value="{{$integracione->Activo}}" checked/>
                                    <span id="slider">
                                    <span id="sliderOn">ON</span>
                                    <span id="sliderOff">OFF</span>
                                    <span id="sliderBlock"></span>
                                    </span>
                                </label>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal Comprobante new -->

    <div id="edit-interacion" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div>
                    <h4 class="card-title">Editar detalle</h4>
                    <div id="divPrueba"></div>
                </div>
                <div class="modal-body">
                    <form data-toggle="validator"  role="form"  method="post" id="formintegracion" name="formintegracion" action="{{url('cuentaintegracion/create')}}">
                        {!! csrf_field() !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group -mb-6" {{ $errors->has('country_name') ? ' has-error' : '' }}>
                                        <label for="country_name" class="form-label">Caja:</label>
                                        <input type="hidden" name="country_name_n" id="country_name_n" class="form-control"  />
                                        <input type="text" name="country_name" id="country_name" class="form-control" placeholder="" />
                                        <div id="countryList">
                                        </div>
                                        {{ csrf_field() }}
                                        <script>
                                            $(document).ready(function(){
                                                $('#country_name').keyup(function(){
                                                    validar_vacios();
                                                    var query = $(this).val();
                                                    if(query != '')
                                                    {
                                                        var _token = $('input[name="_token"]').val();
                                                        $.ajax({
                                                            url:"{{ route('caja.fetch') }}",
                                                            method:"POST",
                                                            data:{query:query, _token:_token},
                                                            success:function(data){
                                                                $('#countryList').fadeIn();
                                                                $('#countryList').html(data);
                                                            }
                                                        });
                                                    }
                                                });
                                                $(document).on('click', '.caja', function(){
                                                    $('#country_name').val($(this).text());
                                                    $('#country_name_n').val($(this).attr("id"));
                                                    $('#countryList').fadeOut();
                                                    validar_vacios();
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group mb-6" {{ $errors->has('country_name') ? ' has-error' : '' }}>
                                        <label for="credito_fiscal" class="form-label">Credito Fiscal:</label>
                                        <input type="hidden" name="credito_fiscal_m" id="credito_fiscal_m" class="form-control" placeholder="" />
                                        <input type="text" name="credito_fiscal" id="credito_fiscal" class="form-control"  />
                                        <div id="credito_fiscal1">
                                        </div>
                                        {{ csrf_field() }}
                                        <script>
                                            $(document).ready(function(){
                                                $('#credito_fiscal').keyup(function(){
                                                    validar_vacios();
                                                    var query = $(this).val();
                                                    if(query != '')
                                                    {
                                                        var _token = $('input[name="_token"]').val();
                                                        $.ajax({
                                                            url:"{{ route('creditofiscal.fetch') }}",
                                                            method:"POST",
                                                            data:{query:query, _token:_token},
                                                            success:function(data){
                                                                $('#credito_fiscal1').fadeIn();
                                                                $('#credito_fiscal1').html(data);
                                                            }
                                                        });
                                                    }
                                                });
                                                $(document).on('click', '.credito', function(){
                                                    $('#credito_fiscal').val($(this).text());
                                                    $('#credito_fiscal_m').val($(this).attr("id"));
                                                    $('#credito_fiscal1').fadeOut();
                                                    validar_vacios();

                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group mb-6" {{ $errors->has('country_name') ? ' has-error' : '' }}>
                                        <label for="dedito_fiscal" class="form-label">Debito Fiscal:</label>
                                        <input type="hidden" name="dedito_fiscal_n" id="dedito_fiscal_n" class="form-control" placeholder="" />
                                        <input type="text" name="dedito_fiscal" id="dedito_fiscal" class="form-control" placeholder="" />
                                        <div id="dedito_fiscal1">
                                        </div>
                                        {{ csrf_field() }}
                                        <script>
                                            $(document).ready(function(){
                                                $('#dedito_fiscal').keyup(function(){
                                                    validar_vacios();
                                                    var query = $(this).val();
                                                    if(query != '')
                                                    {
                                                        var _token = $('input[name="_token"]').val();
                                                        $.ajax({
                                                            url:"{{ route('deditofiscal.fetch') }}",
                                                            method:"POST",
                                                            data:{query:query, _token:_token},
                                                            success:function(data){
                                                                $('#dedito_fiscal1').fadeIn();
                                                                $('#dedito_fiscal1').html(data);
                                                            }
                                                        });
                                                    }
                                                });
                                                $(document).on('click', '.dedito', function(){
                                                    $('#dedito_fiscal').val($(this).text());
                                                    $('#dedito_fiscal_n').val($(this).attr("id"));
                                                    $('#dedito_fiscal1').fadeOut();
                                                    validar_vacios();

                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group mb-6" {{ $errors->has('country_name') ? ' has-error' : '' }}>
                                        <label for="Compra" class="form-label">Compra:</label>
                                        <input type="hidden" name="Compra_m" id="Compra_m" class="form-control" placeholder="" />
                                        <input type="text" name="Compra" id="Compra" class="form-control" placeholder="" />
                                        <div id="Compra1">
                                        </div>
                                        {{ csrf_field() }}
                                        <script>
                                            $(document).ready(function(){
                                                $('#Compra').keyup(function(){
                                                    validar_vacios();
                                                    var query = $(this).val();
                                                    if(query != '')
                                                    {
                                                        var _token = $('input[name="_token"]').val();
                                                        $.ajax({
                                                            url:"{{ route('Compra.fetch') }}",
                                                            method:"POST",
                                                            data:{query:query, _token:_token},
                                                            success:function(data){
                                                                $('#Compra1').fadeIn();
                                                                $('#Compra1').html(data);
                                                            }
                                                        });
                                                    }
                                                });
                                                $(document).on('click', '.Compra', function(){
                                                    $('#Compra').val($(this).text());
                                                    $('#Compra_m').val($(this).attr("id"));
                                                    $('#Compra1').fadeOut();
                                                    validar_vacios();

                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group mb-6" {{ $errors->has('country_name') ? ' has-error' : '' }}>
                                        <label for="Venta" class="form-label">Venta:</label>
                                        <input type="hidden" name="Venta_m" id="Venta_m" class="form-control" placeholder="" />
                                        <input type="text" name="Venta" id="Venta" class="form-control" placeholder="" />
                                        <div id="Venta1">
                                        </div>
                                        {{ csrf_field() }}
                                        <script>
                                            $(document).ready(function(){
                                                $('#Venta').keyup(function(){
                                                    validar_vacios();
                                                    var query = $(this).val();
                                                    if(query != '')
                                                    {
                                                        var _token = $('input[name="_token"]').val();
                                                        $.ajax({
                                                            url:"{{ route('Venta.fetch') }}",
                                                            method:"POST",
                                                            data:{query:query, _token:_token},
                                                            success:function(data){
                                                                $('#Venta1').fadeIn();
                                                                $('#Venta1').html(data);
                                                            }
                                                        });
                                                    }
                                                });
                                                $(document).on('click', '.Venta', function(){
                                                    $('#Venta').val($(this).text());
                                                    $('#Venta_m').val($(this).attr("id"));
                                                    $('#Venta1').fadeOut();
                                                    validar_vacios();

                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group mb-6" {{ $errors->has('country_name') ? ' has-error' : '' }}>
                                        <label for="IT" class="form-label">IT:</label>
                                        <input type="hidden" name="IT_m" id="IT_m" class="form-control" placeholder="" />
                                        <input type="text" name="IT" id="IT" class="form-control" placeholder="" />
                                        <div id="IT1">
                                        </div>
                                        {{ csrf_field() }}
                                        <script>
                                            $(document).ready(function(){
                                                $('#IT').keyup(function(){
                                                    validar_vacios();
                                                    var query = $(this).val();
                                                    if(query != '')
                                                    {
                                                        var _token = $('input[name="_token"]').val();
                                                        $.ajax({
                                                            url:"{{ route('IT.fetch') }}",
                                                            method:"POST",
                                                            data:{query:query, _token:_token},
                                                            success:function(data){
                                                                $('#IT1').fadeIn();
                                                                $('#IT1').html(data);
                                                            }
                                                        });
                                                    }
                                                });
                                                $(document).on('click', '.IT', function(){
                                                    $('#IT').val($(this).text());
                                                    $('#IT_m').val($(this).attr("id"));
                                                    $('#IT1').fadeOut();
                                                    validar_vacios();

                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group mb-6" {{ $errors->has('country_name') ? ' has-error' : '' }}>
                                        <label for="ITxP" class="form-label">IT por pagar:</label>
                                        <input type="hidden" name="ITxP_m" id="ITxP_m" class="form-control" placeholder="" />
                                        <input type="text" name="ITxP" id="ITxP" class="form-control" placeholder="" />
                                        <div id="ITxP1">
                                        </div>
                                        {{ csrf_field() }}
                                        <script>
                                            $(document).ready(function(){
                                                $('#ITxP').keyup(function(){
                                                    validar_vacios();
                                                    var query = $(this).val();
                                                    if(query != '')
                                                    {
                                                        var _token = $('input[name="_token"]').val();
                                                        $.ajax({
                                                            url:"{{ route('ITxP.fetch') }}",
                                                            method:"POST",
                                                            data:{query:query, _token:_token},
                                                            success:function(data){
                                                                $('#ITxP1').fadeIn();
                                                                $('#ITxP1').html(data);
                                                            }
                                                        });
                                                    }
                                                });
                                                $(document).on('click', '.ITxP', function(){
                                                    $('#ITxP').val($(this).text());
                                                    $('#ITxP_m').val($(this).attr("id"));
                                                    $('#ITxP1').fadeOut();
                                                    validar_vacios();

                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right card-body">
                            <button type="button" class="btn btn-default btn-fill" data-dismiss="modal">Cerrar</button>
                            <button type="submit" id="bt_edit" class="btn btn-primary btn-fill">Guardar</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <script>
        $(document).ready(function () {
            validar_vacios();
            $('#btn_edit').click(function () {
                $('#edit-interacion').modal('show');
                // $('#formdetalleventa').trigger("reset");
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('editint.fetch') }}",
                    method:"POST",
                    data:{ _token:_token},
                    success:function(data){
                        separador = ",",
                            arreglo = data.split(separador);
                        $('#country_name_n').val(arreglo[0]);
                        $('#credito_fiscal_m').val(arreglo[1]);
                        $('#dedito_fiscal_n').val(arreglo[2]);
                        $('#Compra_m').val(arreglo[3]);
                        $('#Venta_m').val(arreglo[4]);
                        $('#IT_m').val(arreglo[5]);
                        $('#ITxP_m').val(arreglo[6]);

                        $('#country_name').val(arreglo[7]);
                        $('#credito_fiscal').val(arreglo[8]);
                        $('#dedito_fiscal').val(arreglo[9]);
                        $('#Compra').val(arreglo[10]);
                        $('#Venta').val(arreglo[11]);
                        $('#IT').val(arreglo[12]);
                        $('#ITxP').val(arreglo[13]);
                    }
                });
            });
            var estado = $('#toggle-event').val();
            if( estado == 1){
                document.getElementById('toggle-event').checked = true;
            }else{
                document.getElementById('toggle-event').checked = false;
            }

            $('#toggle-event').change(function() {
                var valor = $(this).prop('checked');
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('actualizar.estado') }}",
                    method:"POST",
                    data:{query:valor, _token:_token},
                    success:function(data){
                        if( data == 1){
                            document.getElementById('toggle-event').checked = true;
                        }else{
                            document.getElementById('toggle-event').checked = false;
                        }
                    }
                });
            });



        });

        function validar_vacios() {
            var caja = $('#country_name').val();
            var credito = $('#credito_fiscal').val();
            var debito = $('#dedito_fiscal').val();
            var compra = $('#Compra').val();
            var venta = $('#Venta').val();
            var it = $('#IT').val();
            var ITxP = $('#ITxP').val();

            if(caja.length < 4 || credito.length < 4|| debito.length < 4|| compra.length < 4|| venta.length < 4|| it.length < 4|| ITxP.length < 4){
                // $('#divPrueba').html("Basio");
                $("#bt_edit").prop("disabled", true);
            }else {
                // $('#divPrueba').html("LLeno");
                $("#bt_edit").prop("disabled", false);
            }
            if(caja.length == 0 || credito.length == 0|| debito.length == 0 || compra.length == 0 || venta.length == 0 || it.length == 0 || ITxP.length == 0){
                // $('#divPrueba').html("Basio");
                $("#bt_edit").prop("disabled", true);
            }



        }
    </script>


@endsection