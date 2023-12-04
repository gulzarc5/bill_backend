<?php

namespace App\Http\Resources\Admin\Quotation;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
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
            'client_id' => $this->client_id,
            'client' => $this->client->name ?? null,
            'client_mobile' => $this->client->mobile ?? null,
            'total_sq_feet' => $this->total_sq_feet,
            'amount' => number_format($this->amount, 2, '.', ''),
            'cgst' => number_format($this->cgst, 2, '.', ''),
            'sgst' => number_format($this->sgst, 2, '.', ''),
            'igst' => number_format($this->igst, 2, '.', ''),
            'total_amount' => number_format($this->total_amount, 2, '.', ''),
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y'),
            'created_by' => $this->creator->name ?? null,
            'details' => QuotationDetailsResource::collection($this->details)
        ];
    }
}
