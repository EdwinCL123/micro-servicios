<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductoController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('productos')->group(function () {
    Route::get('/', [ProductoController::class, 'index']);       // Listar todos los productos
    Route::get('{id}', [ProductoController::class, 'show']);     // Obtener producto por ID
    Route::post('/', [ProductoController::class, 'store']);      // Crear producto
    Route::patch('{id}', [ProductoController::class, 'update']); // Actualizar producto por ID
    Route::delete('{id}', [ProductoController::class, 'destroy']); // Eliminar producto por ID
});

Route::get('/docs', function () {
    return view('l5-swagger::index');
});
