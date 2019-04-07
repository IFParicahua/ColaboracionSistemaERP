<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//Empresa
Route::get('/empresa', 'EmpresaController@index')->name('home');
Route::post('/empresa/create', 'EmpresaController@create');
Route::get('/menus','EmpresaController@vista');
Route::post('/empresa/update', 'EmpresaController@update');
Route::get('/redirect','EmpresaController@redirect');
Route::get('/empresa/{id}/delete','EmpresaController@delete' );
Route::get('/menus', function () {
    return view('menus');
});

//Gestion
Route::get('/gestion', 'GestionController@index')->name('home');
Route::post('/gestion/create', 'GestionController@create');
Route::post('/gestion/update','GestionController@update');
Route::get('/redireccion','GestionController@redir');
Route::get('/gestion/{id}/delete','GestionController@delete' );
Route::get('/gestion/{id}/cerrar','GestionController@cerrar' );

//Periodo
Route::get('/periodo', 'PeriodoController@index');
Route::post('/periodo/create', 'PeriodoController@create');
Route::post('/periodo/update', 'PeriodoController@update');
Route::get('/periodo/{id}/delete','PeriodoController@delete' );

//Cuenta
Route::get('/cuenta',array('as'=>'jquery.treeview','uses'=>'CuentaController@treeView'));
Route::post('/cuenta/create', 'CuentaController@create');
Route::post('/cuenta/update', 'CuentaController@update');
Route::get('/cuenta/{id}/delete','CuentaController@delete');
Route::post('/borrar/fetch', 'CuentaController@bloquearbtn')->name('borrar.fetch');

//Moneda
Route::get('/moneda', 'MonedaController@index');
Route::post('/moneda/create', 'MonedaController@create');

//Comprobante
Route::get('/comprobante', 'ComprobanteController@index');
Route::get('/comprobantenew', 'ComprobanteController@indexnuevo');
Route::post('/comprobante/create','ComprobanteController@crear');
Route::get('/live_search/action', 'ComprobanteController@action')->name('live_search.action');
Route::post('/autocomplete/fetch', 'ComprobanteDetalleController@fetch')->name('autocomplete.fetch');

//Detalle Comprobante
Route::get('/detalle/{id}', 'ComprobanteDetalleController@redirect');
Route::get('/comprobantedetalle', 'ComprobanteDetalleController@index');
Route::post('/comprobantedetalle/editar', 'ComprobanteDetalleController@edit');
Route::get('/form_date/validacion','ComprobanteDetalleController@validation')->name('form_date.validation');
Route::get('/comprobantedetalle/{id}/anular','ComprobanteDetalleController@anular');


//Libro Diario

Route::get('/librodiario', 'LibroDiarioController@index');
Route::post('/librodiario/fetch', 'LibroDiarioController@fetch')->name('LibroDiario.fetch');
Route::get('/libromayor', 'LibroDiarioController@lbmayor');
Route::get('/balanceinicial', 'LibroDiarioController@balanceinicial');
Route::get('/balancegeneral', 'LibroDiarioController@balancegeneral');
Route::get('/resultado', 'LibroDiarioController@estadoresultado');
Route::get('/sumsaldo', 'LibroDiarioController@sumaysaldo');

//Categorias

Route::get('categoria',array('as'=>'jquery.treeview','uses'=>'CategoriaController@treeView'));
Route::post('/categoria/guardar', 'CategoriaController@guardar')->name('categoria.guardar');
Route::post('/categoria/actualizar', 'CategoriaController@actualizar')->name('categoria.actualizar');
Route::get('/categoria/{id}/eliminar','CategoriaController@eliminar');
Route::post('/categoria/fetch', 'ArticuloController@fetch')->name('categoria.fetch');
Route::post('/recuperar/categoria', 'ArticuloController@recuperar')->name('recuperar.categoria');


//Articulos
Route::get('/articulos', 'ArticuloController@index');
Route::post('/articulo/nuevo','ArticuloController@create');
Route::post('/articulo/edit','ArticuloController@update');
Route::get('/articulo/{id}/eliminar','ArticuloController@delete');
Route::post('/edit_articulo/fetch', 'ArticuloController@EditarFetch')->name('edit_articulo.fetch');
Route::post('/articulo/completar', 'ArticuloController@Artfetch')->name('articulo.completar');

//Nota
Route::get('/compra', 'NotaController@index');
Route::get('/notacompra/{id}','NotaController@redictcompra');
Route::get('/notaventa/{id}','NotaController@redirectVenta');
Route::get('/venta', 'NotaController@vista');

//Nueva-Nota-lote
Route::get('/compranew', 'LoteController@viewNuevo');
Route::post('/articulo/fetch', 'LoteController@fetch')->name('articulo.fetch');
Route::post('/buscar/articulo', 'LoteController@fetchs')->name('buscar.articulo');
Route::post('/articulo/valido', 'LoteController@articuloval')->name('articulo.valido');
Route::get('/validar/fecha', 'LoteController@validarfecha')->name('validar.fecha');


Route::post('/nota-lote/nuevo', 'LoteController@crear');
Route::post('/nota-lote/anular', 'LoteController@anular');

//Vista editar lote de compra
Route::get('/ventaedit', 'LoteController@vistaEdit');


//Nueva-Nota-lote
Route::get('/ventanew', 'DetalleVentaController@viewNuevo');
Route::post('/LoteEdit/fetch', 'DetalleVentaController@LoteEdit')->name('LoteEdit.fetch');
Route::get('/cantidad/val', 'DetalleVentaController@validarstock')->name('cantidad.val');
Route::get('/cantidadEdit/val', 'DetalleVentaController@valeditstock')->name('cantidadEdit.val');

//Buscar Articulo Nuevo
Route::post('/articulo/buscar', 'DetalleVentaController@buscar')->name('articulo.buscar');


Route::post('/search/articulo', 'DetalleVentaController@buscararticulo')->name('search.articulo');


//Vista editar detalle de venta
Route::get('/nota-detalle', 'DetalleVentaController@vistaEdit');
Route::post('/detalle-venta/anular', 'DetalleVentaController@anular');


//Venta

Route::get('/lotes/{id}','ArticuloController@Lote');

Route::get('/lotes', 'ArticuloController@LoteVista');

//Nueva-Nota-lote

Route::post('/detalle-nuevo/nuevo', 'DetalleVentaController@crear');
//Buscar Lote nuevo
Route::post('/Lote/fetch', 'DetalleVentaController@fetch')->name('Lote.fetch');
//Buscar Lote Edit
Route::post('/Lote/Edit', 'DetalleVentaController@fetchEdit')->name('Lote.Edit');
//Buscar Precio de Lote Venta
Route::get('/precio/fetch', 'DetalleVentaController@buscar_precio')->name('precio.fetch');
//Buscar Precio y cantida Lote Venta
Route::get('/precioEdit/fetch', 'DetalleVentaController@cant_precio')->name('precioEdit.fetch');


Route::get('/cuentaintegracion','CuentaIntegracionController@index');
Route::post('/caja/fetch', 'CuentaIntegracionController@fetch')->name('caja.fetch');
Route::post('/creditofiscal/fetch', 'CuentaIntegracionController@fetch1')->name('creditofiscal.fetch');
Route::post('/deditofiscal/fetch', 'CuentaIntegracionController@fetch2')->name('deditofiscal.fetch');
Route::post('/Compra/fetch', 'CuentaIntegracionController@fetch3')->name('Compra.fetch');
Route::post('/Venta/fetch', 'CuentaIntegracionController@fetch4')->name('Venta.fetch');
Route::post('/IT/fetch', 'CuentaIntegracionController@fetch5')->name('IT.fetch');
Route::post('/ITxP/fetch', 'CuentaIntegracionController@fetch6')->name('ITxP.fetch');
Route::post('/cuentaintegracion/create', 'CuentaIntegracionController@create');
Route::post('/actualizar/estado', 'CuentaIntegracionController@actualizar')->name('actualizar.estado');
Route::post('/editint/fetch', 'CuentaIntegracionController@fetchEdit')->name('editint.fetch');

Route::post('/edit_articulo/fetch', 'ArticuloController@EditarFetch')->name('edit_articulo.fetch');

Route::post('/articulo/completar', 'ArticuloController@Artfetch')->name('articulo.completar');



