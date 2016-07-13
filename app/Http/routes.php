<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');
Route::get('/jam', 'MainController@jam');
Route::group(['middleware' => ['auth']], function (){
    Route::get('/', function () {
        return redirect()->route('home');
    });
    Route::get('/home', [
        'uses' => 'MainController@home',
        'as' => 'home']);
    Route::post('/absen_now', [
        'uses' => 'MainController@absen_now',
        'as' => 'absen.now'
        ]);
    Route::get('/absen_detail', [
        'uses' => 'MainController@data_absen',
        'as' => 'absen.detail'
        ]);
    Route::get('/cuti', [
        'uses' => 'MainController@cuti',
        'as' => 'cuti'
        ]);
    Route::post('/cuti_temp', [
        'uses' => 'MainController@cuti_temp',
        'as' => 'cuti.add_temp'
        ]);
    Route::post('/cuti_send', [
        'uses' => 'MainController@cuti_send',
        'as' => 'cuti.send_temp'
        ]);
    Route::post('/cuti_batal', [
        'uses' => 'MainController@cuti_batal',
        'as' => 'cuti.batal'
        ]);
    Route::post('/report-absen', [
        'uses' => 'AdminController@printReportAbsen',
        'as' => 'absen.print-report'
        ]);
    Route::get('/report/absen', [
        'uses' => 'AdminController@report_absen',
        'as' => 'report.absen',
        'middleware' => 'admin'
        ]);
    Route::get('/report/karyawan', [
        'uses' => 'AdminController@report_karyawan',
        'as' => 'report.karyawan',
        'middleware' => 'admin'
        ]);
    Route::post('/report-karyawan', [
        'uses' => 'AdminController@printReportKaryawan',
        'as' => 'karyawan.print-report',
        'middleware' => 'admin'
        ]);
    Route::get('/cuti-req', [
        'uses' => 'AdminController@cuti_req',
        'as' => 'cuti.request',
        'middleware' => 'admin'
        ]);
    Route::post('/cuti-req', [
        'uses' => 'AdminController@cuti_aksi',
        'as' => 'cuti.aksi',
        'middleware' => 'admin'
        ]);
    Route::get('/karyawan', [
        'uses' => 'AdminController@karyawan',
        'as' => 'karyawan',
        'middleware' => 'admin'
        ]);
   Route::get('/karyawan/tambah', [
        'uses' => 'AdminController@getAddKaryawan',
        'as' => 'karyawan.tambah',
        'middleware' => 'admin'
        ]);
     Route::post('/karyawan/tambah', [
        'uses' => 'AdminController@postAddKaryawan',
        'as' => 'karyawan.tambah-post',
        'middleware' => 'admin'
        ]);
     Route::get('/karyawan/edit/{id}', [
        'uses' => 'AdminController@getEditKaryawan',
        'as' => 'karyawan.edit',
        'middleware' => 'admin'
        ]);
     Route::post('/karyawan/edit/{id}', [
        'uses' => 'AdminController@postEditKaryawan',
        'as' => 'karyawan.edit-post',
        'middleware' => 'admin'
        ]);
     Route::post('/karyawan/gantipw', [
        'uses' => 'AdminController@gantiPwd',
        'as' => 'karyawan.gantipw',
        'middleware' => 'admin'
        ]);
     Route::post('/karyawan/hapus', [
        'uses' => 'AdminController@hapusUser',
        'as' => 'karyawan.hapus',
        'middleware' => 'admin'
        ]);
     Route::post('/cabang/add', [
        'uses' => 'AdminController@postAddCabang',
        'as' => 'cabang.add',
        'middleware' => 'admin'
        ]);
     Route::post('/cabang/del', [
        'uses' => 'AdminController@postDelCabang',
        'as' => 'cabang.del',
        'middleware' => 'admin'
        ]);
});
     /*Route::get('/cek', function(){
        return view('tes');
     });*/
     Route::get('/cek', 'MainController@tos');