<?php

namespace App\Http\Resources\Admin\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'status' => $this->status,
        ];
    }
}
