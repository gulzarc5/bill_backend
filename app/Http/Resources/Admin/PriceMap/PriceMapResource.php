<?php

namespace App\Http\Resources\Admin\PriceMap;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceMapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    protected static $counter = 0;
    public function toArray($request)
    {
        self::$counter++;
        return [
            'serial_number' => self::$counter,
            'id' => $this->id,
            'material_id' => $this->material_id,
            'material' => $this->material->name ?? null,
            'glass_mm_id' => $this->glass_mm_id,
            'glass_mm' => $this->glassMm->name ?? null,
            'price' => $this->price,
            'bill_price' => $this->bill_price,
            'status' => $this->status,
        ];
    }
}
