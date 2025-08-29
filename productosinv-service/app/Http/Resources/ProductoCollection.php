<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductoCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($producto) {
                return [
                    'type' => 'productos',
                    'id' => (string) $producto->id,
                    'attributes' => [
                        'nombre' => $producto->nombre,
                        'precio' => $producto->precio,
                        'created_at' => $producto->created_at,
                        'updated_at' => $producto->updated_at,
                    ],
                ];
            }),
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $this->currentPage(),
                'from' => $this->firstItem(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'to' => $this->lastItem(),
                'total' => $this->total(),
            ],
        ];
    }
}
