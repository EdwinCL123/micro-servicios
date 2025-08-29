<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
     public function toArray($request)
    {
        return [
            'type' => 'inventarios',
            'id' => (string) $this->producto_id,
            'attributes' => [
                'cantidad' => $this->cantidad,
                'updated_at' => $this->updated_at,
            ],
        ];
    }
}
