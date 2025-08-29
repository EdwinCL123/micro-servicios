<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class InventarioTest extends TestCase
{
    use RefreshDatabase;

    private function withKey() {
        return ['X-API-KEY' => env('INVENTARIO_API_KEY', 'secret123')];
    }

    /** @test */
    public function consulta_inventario_cuando_producto_existe()
    {
        Http::fake([
            '*' => Http::response([
                'data' => [
                    'type' => 'productos',
                    'id' => '1',
                    'attributes' => ['nombre' => 'Mouse', 'precio' => 10.0]
                ]
            ], 200)
        ]);

        $response = $this->withHeaders($this->withKey())
                         ->getJson('/api/inventario/1');

        // Puede ser 200 (ya existía) o 201 (se creó con firstOrCreate)
        $this->assertTrue(in_array($response->status(), [200, 201]),
            "Expected 200 or 201, got {$response->status()} - {$response->getContent()}");

        $response->assertJsonPath('data.type', 'inventarios')
                 ->assertJsonPath('data.id', '1')
                 ->assertJsonStructure(['data' => ['attributes' => ['cantidad']]]);
    }

    /** @test */
    public function actualizar_inventario()
    {
        Http::fake(['*' => Http::response(['data' => ['id' => '2']], 200)]);

        $response = $this->withHeaders($this->withKey())
                         ->patchJson('/api/inventario/2', ['cantidad' => 25]);

        // Igual: puede devolver 201 la primera vez
        $this->assertTrue(in_array($response->status(), [200, 201]),
            "Expected 200 or 201, got {$response->status()} - {$response->getContent()}");

        $response->assertJsonPath('data.id', '2')
                 ->assertJsonPath('data.attributes.cantidad', 25);
    }

    /** @test */
 /** @test */
/** @test */
/** @test */
/** @test */






    /** @test */
    public function devuelve_500_si_productos_no_responde()
    {
        Http::fake(['*' => Http::response(null, 500)]);

        $this->withHeaders($this->withKey())
             ->getJson('/api/inventario/1')
             ->assertStatus(500);
    }

    /** @test */
    public function protege_por_api_key()
    {
        $this->getJson('/api/inventario/1')->assertStatus(401);
    }
}
