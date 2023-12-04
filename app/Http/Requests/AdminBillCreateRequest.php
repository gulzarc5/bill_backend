<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminBillCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->type == 1 || $this->user()->type == 2;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'mobile' => 'required|numeric|digits:10',
            'products' => 'required|array|min:1',
            'e_way_bill_no' => 'nullable|string|max:100',
            'eWayBillDate' => 'nullable|date',
            'e_way_bill_valid_date' => 'nullable|date',
            'supply_type' => 'nullable|string|in:B2B,B2C',//take only B2B or B2C
            'client_igst' => 'nullable|string|max:100',
            'discount' => 'nullable|numeric|gte:0',
            'transportCharge' => 'nullable|numeric|gte:0',
            'cash_recieved' => 'nullable|numeric|gte:0',
            'is_same' => 'required|in:1,2',
            'recipient_name' => 'required_if:is_same,2|nullable|string|max:100',
            'recipient_mobile' => 'required_if:is_same,2|nullable|numeric|digits:10',
            'totalCgst' => 'nullable|numeric',
            'totalSgst' => 'nullable|numeric',
            'totalIgst' => 'nullable|numeric',
            'totalSqFeet' => 'nullable|numeric',
        ];
    }
}
