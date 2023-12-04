<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Bill\BillResource;
use App\Http\Resources\Admin\Quotation\QuotationResource;
use App\Models\Bill;
use App\Models\Quotation;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function fetch(Request $request){
        $this->validate($request,[
            'startDate' => 'required|date',
            'toDate' => 'required|date|after_or_equal:startDate',
            'client_id' => 'nullable|numeric|exists:buyers,id',
            'type' => 'required|in:1,2',
        ]);

        if ($request->type == 1) {
            $response = $this->fetchBill($request);
        } else {
            $response = $this->fetchQuotation($request);
        }
        return response()->json($response, 200);
    }

    private function fetchBill(Request $request){
        $data = Bill::whereDate('created_at','>=', $request->startDate)->whereDate('created_at','<=', $request->toDate);
        if ($request->client_id) {
            $data = $data->where('client_id',$request->client_id);
        }
        $count = $data->count();
        $amount = $data->sum('amount');
        $cgst = $data->sum('cgst');
        $sgst = $data->sum('sgst');
        $igst = $data->sum('client_igst');
        $transport_charge = $data->sum('transport_charge');
        $outstanding_amount = $data->sum('outstanding_amount');
        $discount = $data->sum('discount');
        $cash_recieved = $data->sum('cash_recieved');
        $round_off_amount = $data->sum('round_off_amount');
        $bills = $data->latest()->get();
        $response = [
            'status' => true,
            'message' => 'Bills List',
            'count' => $count,
            'amount' => number_format($amount,2),
            'cgst' => number_format($cgst,2),
            'sgst' => number_format($sgst,2),
            'igst' => number_format($igst,2),
            'transport_charges' => number_format($transport_charge,2),
            'discount' => number_format($discount,2),
            'outstanding_amount' => number_format($outstanding_amount,2),
            'cash_recieved' => number_format($cash_recieved,2),
            'round_off_amount' => number_format($round_off_amount,2),
            'data' => BillResource::collection($bills),
        ];
        return $response;
    }
    
    private function fetchQuotation(Request $request){
        $data = Quotation::whereDate('created_at','>=', $request->startDate)->whereDate('created_at','<=', $request->toDate);
        if ($request->client_id) {
            $data = $data->where('client_id',$request->client_id);
        }
        $count = $data->count();
        $amount = $data->sum('amount');
        $cgst = $data->sum('cgst');
        $sgst = $data->sum('sgst');
        $igst = $data->sum('igst');
        $total_amount = $data->sum('total_amount');
        $quotations = $data->latest()->get();
        $response = [
            'status' => true,
            'message' => 'Quotation List',
            'count' => $count,
            'amount' => number_format($amount,2),
            'cgst' => number_format($cgst,2),
            'sgst' => number_format($sgst,2),
            'igst' => number_format($igst,2),
            'total_amount' => number_format($total_amount,2),
            'data' => QuotationResource::collection($quotations),
        ];
        return $response;
    }
}
