<?php

namespace App\Http\Resources\Admin\Material;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
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
            'name' => $this->name,
            'glass_mm_id' => $this->glass_mm_id,
            'glassMm' => $this->glassMm->name ?? null,
            'per_sq_feet_amount' => $this->amount,
            'per_milli_sq_feet_amount' => $this->milli_amount,
            'status' => $this->status,
        ];
    }
}
