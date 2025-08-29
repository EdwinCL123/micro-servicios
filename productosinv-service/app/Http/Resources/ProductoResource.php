<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'productos',       // Tipo del recurso
            'id' => (string) $this->id,  // ID como string, estándar JSON API
            'attributes' => [            // Todos los atributos van aquí
                'nombre' => $this->nombre,
                'precio' => $this->precio,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
        ];
    }
}
