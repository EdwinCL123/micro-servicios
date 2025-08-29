<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Http\Resources\ProductoResource;
use App\Http\Resources\ProductoCollection;

/**
 * @OA\Info(
 *      title="API Productos",
 *      version="1.0.0",
 *      description="Documentación de la API de Productos"
 * )
 */
class ProductoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/productos",
     *     summary="Listar todos los productos (paginados)",
     *     tags={"Productos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos obtenida correctamente"
     *     )
     * )
     */
    public function index()
    {
        $productos = Producto::paginate(10);
        return new ProductoCollection($productos);
    }

    /**
     * @OA\Get(
     *     path="/productos/{id}",
     *     summary="Obtener un producto por ID",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Producto encontrado"),
     *     @OA\Response(response=404, description="Producto no encontrado")
     * )
     */
    public function show($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json([
                'errors' => [[
                    'status' => '404',
                    'title' => 'Not Found',
                    'detail' => 'Producto no encontrado'
                ]]
            ], 404);
        }

        return new ProductoResource($producto);
    }

    /**
     * @OA\Post(
     *     path="/productos",
     *     summary="Crear un nuevo producto",
     *     tags={"Productos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","precio"},
     *             @OA\Property(property="nombre", type="string", example="Laptop"),
     *             @OA\Property(property="precio", type="number", example="1200.50")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Producto creado exitosamente"),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'precio' => 'required|numeric|min:0'
        ]);

        $producto = Producto::create($validated);
        return (new ProductoResource($producto))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * @OA\Patch(
     *     path="/productos/{id}",
     *     summary="Actualizar un producto existente",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Laptop Gamer"),
     *             @OA\Property(property="precio", type="number", example="1500.00")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Producto actualizado"),
     *     @OA\Response(response=404, description="Producto no encontrado")
     * )
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json([
                'errors' => [[
                    'status' => '404',
                    'title' => 'Not Found',
                    'detail' => 'Producto no encontrado'
                ]]
            ], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string',
            'precio' => 'sometimes|numeric|min:0'
        ]);

        $producto->update($validated);
        return new ProductoResource($producto);
    }

    /**
     * @OA\Delete(
     *     path="/productos/{id}",
     *     summary="Eliminar un producto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Producto eliminado"),
     *     @OA\Response(response=404, description="Producto no encontrado")
     * )
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json([
                'errors' => [[
                    'status' => '404',
                    'title' => 'Not Found',
                    'detail' => 'Producto no encontrado'
                ]]
            ], 404);
        }

        $producto->delete();
        return response()->json([], 204);
    }
}
