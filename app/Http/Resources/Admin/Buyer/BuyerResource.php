<?php

namespace App\Http\Resources\Admin\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuyerResource extends JsonResource
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
            'email' => $this->email,
            'mobile' => $this->mobile,
            'state' => $this->state,
            'city' => $this->city,
            'pin' => $this->pin,
            'address' => $this->address,
            'gst_number' => $this->gst_number,
            'status' => $this->status,
        ];
    }
}
