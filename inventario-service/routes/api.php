<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\InventarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/documentacion/inventario', function () {
    return view('l5-swagger::index');
});

Route::get('/inventario/{id}', [InventarioController::class, 'show']);
Route::put('/inventario/{id}', [InventarioController::class, 'update']);


