<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Producto;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_producto()
    {
        $payload = ['nombre' => 'Teclado', 'precio' => 49.90];

        $r = $this->withHeaders(['X-API-KEY' => env('PRODUCTOS_API_KEY', 'secret123')])
                  ->postJson('/api/productos', $payload);

        $r->assertCreated()
          ->assertJsonPath('data.attributes.nombre', 'Teclado')
          ->assertJsonPath('data.attributes.precio', 49.90);
    }

    /** @test */
    public function valida_datos_al_crear()
    {
        $r = $this->withHeaders(['X-API-KEY' => env('PRODUCTOS_API_KEY', 'secret123')])
                  ->postJson('/api/productos', ['nombre' => '', 'precio' => -3]);

        $r->assertStatus(422)
          ->assertJsonStructure(['errors']);
    }

    /** @test */
    public function puede_actualizar_y_ver_por_id()
    {
        $p = Producto::factory()->create();

        $this->withHeaders(['X-API-KEY' => env('PRODUCTOS_API_KEY', 'secret123')])
             ->patchJson("/api/productos/{$p->id}", ['precio' => 79.99])
             ->assertOk()
             ->assertJsonPath('data.attributes.precio', 79.99);

        $this->withHeaders(['X-API-KEY' => env('PRODUCTOS_API_KEY', 'secret123')])
             ->getJson("/api/productos/{$p->id}")
             ->assertOk()
             ->assertJsonPath('data.id', (string) $p->id);
    }

    /** @test */
    public function devuelve_404_si_no_existe()
    {
        $this->withHeaders(['X-API-KEY' => env('PRODUCTOS_API_KEY', 'secret123')])
             ->getJson('/api/productos/999999')
             ->assertStatus(404)
             ->assertJsonStructure(['errors']);
    }

    /** @test */
    public function protege_por_api_key()
    {
        $this->postJson('/api/productos', ['nombre' => 'X', 'precio' => 1])
             ->assertStatus(401);
    }
}
