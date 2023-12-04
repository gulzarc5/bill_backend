<?php

namespace App\Http\Resources\Admin\Bill;

use App\Http\Resources\Admin\Buyer\BuyerResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
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
            'irn_no' => $this->irn_no,
            'ack_no' => $this->ack_no,
            'doc_no' => $this->doc_no,
            'e_way_bill_no' => $this->e_way_bill_no,
            'e_way_bill_date' => $this->e_way_bill_rate,
            'e_way_bill_valid_date' => $this->e_way_bill_valid_date,
            'e_way_bill_valid_date' => $this->e_way_bill_valid_date,
            'client_details' => BuyerResource::make($this->client),
            'client' => $this->client->name ?? null,
            'is_same' => $this->is_same == 1 ? 'Yes' : 'No',
            'recipient_name' => $this->recipient_name,
            'recipient_mobile' => $this->recipient_mobile,
            'supply_type' => $this->supply_type,
            'client_igst' => $this->client_igst,
            'total_sq_feet' => $this->total_sq_feet,
            'discount' => $this->discount,
            'cash_recieved' => $this->cash_recieved,
            'outstanding_amount' => $this->outstanding_amount,
            'transport_charge' => $this->transport_charge,
            'round_off_amount' => $this->round_off_amount,
            'amount' => $this->amount,
            'cgst' => $this->cgst,
            'sgst' => $this->sgst,
            'igst' => $this->client_igst,
            'total_amount' => $this->total_amount ,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y'),
            'details' => BillDetailsResource::collection($this->details)
        ];
    }
}
