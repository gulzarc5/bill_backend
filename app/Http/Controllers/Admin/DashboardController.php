<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Bill\BillResource;
use App\Models\Bill;
use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    function dashboard(Request $request){
        if ($request->user()->type == 1) {
            $quotation = Quotation::whereDate('created_at',Carbon::today());
            $bill = Bill::whereDate('created_at',Carbon::today());
            $today_quotation_generated = $quotation->count();
            $today_bill_generated = $bill->count();
            $today_quotation_amount = $quotation->sum('total_amount');
            $today_bill_amount = $bill->sum('total_amount');
            $bills = Bill::latest()->limit(10)->get();
        } else {
            $quotation = Quotation::where('added_by',$request->user()->id)->whereDate('created_at',Carbon::today());
            $bill = Bill::where('added_by',$request->user()->id)->whereDate('created_at',Carbon::today());
            $today_quotation_generated = $quotation->count();
            $today_bill_generated = $bill->count();
            $today_quotation_amount = $quotation->sum('total_amount');
            $today_bill_amount = $bill->sum('total_amount');
            $bills = Bill::where('added_by',$request->user()->id)->latest()->limit(10)->get();
        }
        

        $response = [
            'status' => true,
            'message' => 'Dashboard',
            'today_quotation_generated' => $today_quotation_generated,
            'today_bill_generated' => $today_bill_generated,
            'today_quotation_amount' => number_format($today_quotation_amount,2),
            'today_bill_amount' => number_format($today_bill_amount,2),
            'data' => BillResource::collection($bills),
        ];
        return response()->json($response, 200);
    }
}
