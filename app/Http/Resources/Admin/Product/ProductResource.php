<?php

namespace App\Http\Resources\Admin\Product;

use App\Http\Resources\Admin\PriceMap\PriceMapResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'hsn_code' => $this->hsn_code,
            'category_id' => $this->category_id ?? null,
            'category_name' => $this->category->name ?? null,
            'material_id' => $this->material_id ?? null,
            'material_name' => $this->material->name ?? null,
            'image' => isset($this->image) ? asset('backend_images/'.$this->image) : "Image Not Found",
            'height' => $this->height,
            'width' => $this->width,
            'glass_mm_id' => $this->glass_mm_id,
            'glass_mm' => $this->glassMm->name ?? null,
            'brand_id' => $this->brand_id,
            'brand' => $this->brand->name ?? null,
            'item_code' => $this->item_code,
            'description' => $this->description,
            'location' => $this->location,
            'Accesories' => $this->Accesories,
            'area_sqfeet' => $this->area_sqfeet,
            'total_price' => $this->total_price,
            'billing_price' => $this->billing_price,
            'status' => $this->status,
            'price_maps' => !empty($this->priceMaps) ? PriceMapResource::collection($this->priceMaps) : [],
        ];
    }
}
