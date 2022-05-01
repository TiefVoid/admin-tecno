<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InfraestructuraController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\StaffController;

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

Route::prefix('area')->group(function(){
    Route::get('/view',[AreaController::class, 'allAreas']);
    Route::get('/view/{id}',[AreaController::class, 'areaById']);
    Route::delete('/del/{id}',[AreaController::class, 'delArea']);
    Route::post('/new',[AreaController::class, 'addArea']);
    Route::put('/edit/{id}',[AreaController::class, 'editArea']);
});

Route::prefix('marca')->group(function(){
    Route::get('/view',[MarcaController::class, 'allMarcas']);
    Route::get('/view/{id}',[MarcaController::class, 'marcaById']);
    Route::delete('/del/{id}',[MarcaController::class, 'delMarca']);
    Route::post('/new',[MarcaController::class, 'addMarca']);
    Route::put('/edit/{id}',[arcaController::class, 'editMarca']);
});

Route::prefix('modelo')->group(function(){
    Route::get('/view',[ModeloController::class, 'allModels']);
    Route::get('/view/{id}',[ModeloController::class, 'modelById']);
    Route::delete('/del/{id}',[ModeloController::class, 'delModel']);
    Route::post('/new',[ModeloController::class, 'addModel']);
    Route::put('/edit/{id}',[ModeloController::class, 'editModel']);
});

Route::prefix('staff')->group(function(){
    Route::get('/view',[StaffController::class, 'allStaff']);
    Route::get('/view/{id}',[StaffController::class, 'staffById']);
    Route::delete('/del/{id}',[StaffController::class, 'delStaff']);
    Route::post('/new',[StaffController::class, 'addStaff']);
    Route::put('/edit/{id}',[StaffController::class, 'editStaff']);
});