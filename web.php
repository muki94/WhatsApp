<?php

use App\Models\LogKirimWa;
use App\Models\smsreminder;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mainController;

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

Route::get('data_hari_ini', function () {
    $data = smsreminder::get_data_hari_ini();
    return response()->json(['total' => count($data), 'data' => $data]);
});

Route::get('hutang_hari_ini', function () {
    $data = hutang::get_data_hari_ini();
    return response()->json(['total' => count($data), 'data' => $data]);
});

Route::get('log_kirim_wa', function () {
    $data = LogKirimWa::all();
    return response()->json(['total' => count($data), 'data' => $data]);
});

Route::controller(mainController::class)->group(function () {
    Route::get('kirim_pesan', 'kirim');
});

Route::controller(mainController::class)->group(function () {
    Route::get('send_pesan', 'send');
});

Route::controller(mainController::class)->group(function () {
    Route::get('cek_pesan', 'cek');
});

Route::controller(mainController::class)->group(function () {
    Route::any('kirim_wa_registrasi', 'kirim_wa_registrasi');
});

Route::get('phpinfo', function () {
    return phpinfo();
});
