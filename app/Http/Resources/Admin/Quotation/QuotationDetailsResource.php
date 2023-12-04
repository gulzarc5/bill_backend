<?php

namespace App\Http\Resources\Admin\Quotation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'hsn_code' => $this->product->hsn_code ?? null,
            'image' => isset($this->product->image) ? url('/api/admin/images/'.$this->product->image.'') : "Image Not Found",
            'location' => $this->product->location ?? null,
            'description' => $this->product->description ?? null,
            'Accesories' => $this->product->Accesories ?? null,
            'brand' => $this->product->brand->name ?? null,
            'category' => $this->product->category->name ?? null,
            'material' => $this->product->material->name ?? null,
            'category_name' => $this->category_name,
            'glass_mm' => $this->glass_mm,
            'material_name' => $this->material_name,
            'height' => $this->height,
            'width' => $this->width,
            'per_sqfeet_amount' => number_format($this->per_sqfeet_amount, 2, '.', ''),
            'quantity' => $this->quantity,
            'total_sq_feet' => number_format($this->total_sq_feet, 2, '.', ''),
        ];
    }
}
