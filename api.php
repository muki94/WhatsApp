<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mainController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(mainController::class)->group(function () {
    Route::any('kirim_wa_registrasi', 'kirim_wa_registrasi');
});

Route::controller(mainController::class)->group(function () {
    route::any('kirim_wa', 'kirim_wa');
});

Route::controller(mainController::class)->group(function () {
    Route::any('kirim_wa_hutang', 'kirim_wa_hutang');
});

Route::controller(mainController::class)->group(function () {
    Route::any('kirim_wa_piutang', 'kirim_wa_piutang');
});
