<?php

namespace App\Http\Resources\Admin\Report;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'bill_id' => $this->bill_id,
            'client_id' => $this->client_id,
            'client' => $this->client->name ?? null,
            'client_mobile' => $this->client->mobile ?? null,
            'type' => $this->type,
            'comment' => $this->comment,
            'amount' => $this->amount,
            'outstanding_amount' => $this->outstanding_amount,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y, h:i A'),
        ];
    }
}
