<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InfraestructuraController;
use App\Http\Controllers\TipoController;

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

Route::prefix('infra') -> group(function(){
    Route::get('/view',[InfraestructuraController::class, 'showInfra']);
    Route::get('/view/{id}',[InfraestructuraController::class, 'showInfraById']);
    Route::get('/cat/{type}',[InfraestructuraController::class, 'showInfraByType']);
    Route::post('/new',[InfraestructuraController::class, 'addInfra']);
    Route::delete('/del/{id}',[InfraestructuraController::class, 'delInfra']);
});

Route::prefix('cat')->group(function(){
    Route::get('/view',[TipoController::class, 'allTypes']);
    Route::get('/view/{id}',[TipoController::class, 'typeById']);
    Route::delete('/del/{id}',[TipoController::class, 'delType']);
    Route::post('/new',[TipoController::class, 'addType']);
    Route::put('/edit/{id}',[TipoController::class, 'editType']);
});