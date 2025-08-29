<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Http\Resources\ProductoResource;
use App\Http\Resources\ProductoCollection;

/**
 * @OA\Info(title="API Productos", version="1.0.0")
 */
class ProductoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/productos/{id}",
     *     summary="Obtener un producto por ID",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    // Listar productos con paginación
   public function index()
    {
        $productos = Producto::paginate(10);
        return new ProductoCollection($productos);
    }

    // Obtener un producto por ID
    public function show($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json([
                'errors' => [
                    [
                        'status' => '404',
                        'title' => 'Not Found',
                        'detail' => 'Producto no encontrado'
                    ]
                ]
            ], 404);
        }

        return new ProductoResource($producto);
    }

    // Crear un nuevo producto
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'precio' => 'required|numeric|min:0'
        ]);

        if (!$validated) {
            return response()->json([
                'errors' => [
                    [
                        'status' => '422',
                        'title' => 'Validation Error',
                        'detail' => 'Datos inválidos para crear el producto'
                    ]
                ]
            ], 422);
        }

        $producto = Producto::create($validated);
        return (new ProductoResource($producto))
                ->response()
                ->setStatusCode(201);
    }

    // Actualizar un producto
    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json([
                'errors' => [
                    [
                        'status' => '404',
                        'title' => 'Not Found',
                        'detail' => 'Producto no encontrado'
                    ]
                ]
            ], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string',
            'precio' => 'sometimes|numeric|min:0'
        ]);

        if (!$validated) {
            return response()->json([
                'errors' => [
                    [
                        'status' => '422',
                        'title' => 'Validation Error',
                        'detail' => 'Datos inválidos para actualizar el producto'
                    ]
                ]
            ], 422);
        }

        $producto->update($validated);
        return new ProductoResource($producto);
    }

    // Eliminar un producto
    public function destroy($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json([
                'errors' => [
                    [
                        'status' => '404',
                        'title' => 'Not Found',
                        'detail' => 'Producto no encontrado'
                    ]
                ]
            ], 404);
        }

        $producto->delete();
        return response()->json([], 204);
    }
}
