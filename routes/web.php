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
})->name('welcome');

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');

Route::auth();

Route::get('logout', 'Auth\LoginController@logout', function () {
    return abort(404);
});

//Route::get('/home', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');

//Produk
Route::get('/product','TokoController\ProductController@index')->name('pdIndex');
Route::get('/product/create','TokoController\ProductController@create')->name('pdCreate');
Route::get('/product/edit/{id}','TokoController\ProductController@edit')->name('pdEdit');
Route::post('/product/store','TokoController\ProductController@store')->name('pdStore');
Route::put('/product/update/{id}','TokoController\ProductController@update')->name('pdUpdate');
Route::delete('/product/delete','TokoController\ProductController@delete')->name('pdDelete');
Route::post('/product/import','TokoController\ProductController@import')->name('pdImport');
Route::post('/product/delete_all','TokoController\ProductController@deleteAll')->name('pdDeleteAll');
Route::get('/product/export','TokoController\ProductController@export')->name('pdExport');

//Gudang
// Route::get('/warehouse','Tokoku\WhController@index')->name('whIndex');
// Route::get('/warehouse/create','Tokoku\WhController@create')->name('whCreate');
// Route::get('/warehouse/edit/{id}','Tokoku\WhController@edit')->name('whEdit');
// Route::post('/warehouse/store','Tokoku\WhController@store')->name('whStore');
// Route::put('/warehouse/update/{id}','Tokoku\WhController@update')->name('whUpdate');
// Route::delete('/warehouse/delete','Tokoku\WhController@delete')->name('whDelete');

//Periode
// Route::get('/periode','Tokoku\PeriodeController@index')->name('periodeIndex');
// Route::get('/periode/create','Tokoku\PeriodeController@create')->name('periodeCreate');
// Route::get('/periode/edit/{id}','Tokoku\PeriodeController@edit')->name('periodeEdit');
// Route::post('/periode/store','Tokoku\PeriodeController@store')->name('periodeStore');
// Route::put('/periode/update/{id}','Tokoku\PeriodeController@update')->name('periodeUpdate');
// Route::delete('/periode/delete','Tokoku\PeriodeController@delete')->name('periodeDelete');

//Transaksi
Route::get('/transaction','TokoController\TrController@index')->name('trIndex');
Route::get('/transaction/create','TokoController\TrController@create')->name('trCreate');
Route::post('/transaction/store','TokoController\TrController@store')->name('trStore');
Route::delete('/transaction/delete','TokoController\TrController@delete')->name('trDelete');

//Stok Opname
Route::get('/stockopname','TokoController\SoController@index')->name('soIndex');
Route::get('/stockopname/create','TokoController\SoController@create')->name('soCreate');
Route::get('/stockopname/edit/{id}','TokoController\SoController@edit')->name('soEdit');
Route::post('/stockopname/store','TokoController\SoController@store')->name('soStore');
Route::put('/stockopname/update/{id}','TokoController\SoController@update')->name('soUpdate');
Route::delete('/stockopname/delete','TokoController\SoController@delete')->name('soDelete');

//Laporan Stok
Route::get('/stockreport','TokoController\WsController@index')->name('wsIndex');
Route::get('/stockreport/{id}','TokoController\WsController@sortByWarehouse')->name('wsSortByWh');
Route::get('/stockreport/{id}/export','TokoController\WsController@export')->name('wsExport');

//Ubah Sandi
Route::get('/password','TokoController\PasswordController@index')->name('passwordChange');
Route::post('/password/update','TokoController\PasswordController@update')->name('passwordChanged');

//Laporan Excel
Route::get('/laporanexcel','TokoController\LaporanController@index')->name('lapIndex');
Route::post('/laporanexcel/exportlaporan','TokoController\LaporanController@exportLaporan')->name('lapExport');



