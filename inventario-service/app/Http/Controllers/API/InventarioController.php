<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Http\Resources\InventarioResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InventarioController extends Controller
{
    // Consultar inventario
  // app/Http/Controllers/API/InventarioController.php

public function show($producto_id)
{
    try {
        // 1) Resolver base_url y api_key con fallback a env()
        $baseUrl = rtrim(config('services.productos.base_url'), '/');
        $apiKey  = config('services.productos.api_key');


        // 2) Construir URL y llamar a Productos con Accept JSON
        $url = "{$baseUrl}/api/productos/{$producto_id}";
        $response = \Illuminate\Support\Facades\Http::withHeaders([
                'X-API-KEY' => $apiKey,
            ])
            ->acceptJson()           // equivale a header Accept: application/json
            ->timeout(5)
            ->retry(2, 1000)
            ->get($url);

        // (Debug opcional)
        if (config('app.debug')) {
            \Log::info('Inventario -> Productos', [
                'url'    => $url,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        }

        // 3) Mapeo explícito de estados antes de failed()
        if ($response->status() === 404) {
            return response()->json([
                'errors' => [[
                    'status' => '404',
                    'title'  => 'Not Found',
                    'detail' => 'Producto no encontrado en microservicio Productos',
                ]],
            ], 404);
        }

        if ($response->status() === 401 || $response->status() === 403) {
            return response()->json([
                'errors' => [[
                    'status' => (string) $response->status(),
                    'title'  => 'Unauthorized',
                    'detail' => 'API Key inválida o faltante para microservicio Productos',
                ]],
            ], 401);
        }

        // 4) Otros fallos (5xx / errores de red)
        if ($response->failed()) {
            return response()->json([
                'errors' => [[
                    'status' => '500',
                    'title'  => 'Service Unavailable',
                    'detail' => 'Error al comunicarse con microservicio Productos',
                ]],
            ], 500);
        }

    } catch (\Throwable $e) {
        // (Debug opcional)
        \Log::warning('Error llamando a Productos', [
            'exception' => $e->getMessage(),
            'producto_id' => $producto_id,
        ]);

        return response()->json([
            'errors' => [[
                'status' => '500',
                'title'  => 'Service Unavailable',
                'detail' => 'Error al comunicarse con microservicio Productos',
            ]],
        ], 500);
    }

    // 5) Inventario existente o creación perezosa
    $inventario = \App\Models\Inventario::firstOrCreate(
        ['producto_id' => $producto_id],
        ['cantidad' => 0]
    );

    // Si prefieres 201 la primera vez que se crea, deja estas 2 líneas;
    // si no, devuélvelo siempre con 200 usando solo "return new InventarioResource(...)"
    $status = $inventario->wasRecentlyCreated ? 201 : 200;
    return (new \App\Http\Resources\InventarioResource($inventario))
        ->response()
        ->setStatusCode($status);
}




    // Actualizar inventario
    public function update(Request $request, $producto_id)
    {
        $validated = $request->validate([
            'cantidad' => 'required|integer|min:0'
        ]);

        $inventario = Inventario::firstOrCreate(['producto_id' => $producto_id]);
        $inventario->cantidad = $validated['cantidad'];
        $inventario->save();

        Log::info("Inventario del producto {$producto_id} actualizado a {$validated['cantidad']}");

        // 201 si se acaba de crear, 200 si solo se actualizó
        $status = $inventario->wasRecentlyCreated ? 201 : 200;

        return (new InventarioResource($inventario))
                    ->response()
                    ->setStatusCode($status);
    }

}
