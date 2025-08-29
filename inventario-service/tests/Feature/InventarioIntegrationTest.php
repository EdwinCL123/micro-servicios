<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class InventarioIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected string $productosBase;
    protected array $productosHeaders;
    protected array $inventarioHeaders;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productosBase = rtrim(env('PRODUCTOS_SERVICE_URL', 'http://127.0.0.1:8000'), '/');
        $this->productosHeaders = [
            'X-API-KEY'    => env('PRODUCTOS_API_KEY', 'secret123'),
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $this->inventarioHeaders = [
            'X-API-KEY' => env('INVENTARIO_API_KEY', 'secret123'),
            'Accept'    => 'application/json',
        ];
    }

    /** @test */
    public function flujo_real_crea_producto_actualiza_y_consulta_inventario()
    {
        // 1) Crear producto real en Productos
        $create = Http::withHeaders($this->productosHeaders)->post("{$this->productosBase}/api/productos", [
            'nombre' => 'Prod Integración',
            'precio' => 99.99,
        ]);
        $this->assertTrue($create->successful(), "Falló crear producto: {$create->status()} - {$create->body()}");
        $productoId = data_get($create->json(), 'data.id');

        try {
            // 2) Actualizar inventario
            $response = $this->withHeaders($this->inventarioHeaders)
                             ->patchJson("/api/inventario/{$productoId}", ['cantidad' => 15]);

            // Acepta 200 (update) o 201 (creado)
            $this->assertTrue(in_array($response->status(), [200, 201]),
                "Expected 200 or 201, got {$response->status()} - {$response->getContent()}");

            $response->assertJsonPath('data.attributes.cantidad', 15);

            // 3) Consultar inventario
            $this->withHeaders($this->inventarioHeaders)
                 ->getJson("/api/inventario/{$productoId}")
                 ->assertOk()
                 ->assertJsonPath('data.id', (string) $productoId)
                 ->assertJsonPath('data.attributes.cantidad', 15);

        } finally {
            // 4) Limpieza: borrar el producto creado en Productos
            Http::withHeaders($this->productosHeaders)
                ->delete("{$this->productosBase}/api/productos/{$productoId}");
        }
    }


    /** @test */
    // public function devuelve_404_en_inventario_si_el_producto_no_existe_en_productos()
    // {
    //     // Config base (ajusta si usas otros)
    //     $productosBase = rtrim(env('PRODUCTOS_SERVICE_URL', 'http://127.0.0.1:8000'), '/');
    //     $productosHeaders = [
    //         'X-API-KEY'    => env('PRODUCTOS_API_KEY', 'secret123'),
    //         'Accept'       => 'application/json',
    //         'Content-Type' => 'application/json',
    //     ];
    //     $inventarioHeaders = [
    //         'X-API-KEY' => env('INVENTARIO_API_KEY', 'secret123'),
    //         'Accept'    => 'application/json',
    //     ];

    //     // Aseguramos que el ID no exista en Productos (debe devolver 404)
    //     $nonExistentId = 999999; // algo grande que seguro no exista
    //     $probe = \Illuminate\Support\Facades\Http::withHeaders($productosHeaders)
    //                 ->get("{$productosBase}/api/productos/{$nonExistentId}");
    //     // Si por alguna razón existe, falla el test
    //     $this->assertEquals(404, $probe->status(), "El producto {$nonExistentId} inesperadamente existe en Productos durante la prueba.");

    //     // Ahora pedimos el inventario de ese ID y esperamos 404 también
    //     $this->withHeaders($inventarioHeaders)
    //         ->getJson("/api/inventario/{$nonExistentId}")
    //         ->assertStatus(404)
    //         ->assertJsonStructure(['errors' => [['status','title','detail']]]);
    // }

}
