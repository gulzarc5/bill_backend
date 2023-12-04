<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Report\TransactionResource;
use App\Models\Buyer;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TrnsactionController extends Controller
{
    public function fetch(Request $request){
        $this->validate($request,[
            'startDate' => 'required|date',
            'toDate' => 'required|date|after_or_equal:startDate',
            'client_id' => 'required|numeric|exists:buyers,id',
        ]);
        $transactions = Transaction::where('client_id',$request->client_id)->whereDate('created_at','>=', $request->startDate)->whereDate('created_at','<=', $request->toDate)->orderBy('id','desc')->get();

        $total_sale = Transaction::where('client_id',$request->client_id)->whereDate('created_at','>=', $request->startDate)->whereDate('created_at','<=', $request->toDate)->where('type',1)->sum('amount');

        $cash_recieved = Transaction::where('client_id',$request->client_id)->whereDate('created_at','>=', $request->startDate)->whereDate('created_at','<=', $request->toDate)->where('type',2)->sum('amount');

        $buyer = Buyer::find($request->client_id);

        if (!empty($transactions) && !empty($total_sale) && !empty($cash_recieved)) {
            $response = [
                'status' => true,
                'message' => 'List',
                'sale' => $total_sale,
                'outstanding_amount' => $buyer->outstanding_amount,
                'cash_recieved' => $cash_recieved,
                'data' => TransactionResource::collection($transactions),
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'List',
                'sale' => null,
                'outstanding_amount' => 00,
                'cash_recieved' => 00,
                'data' => null,
            ];
        }
        
        return response()->json($response, 200);
    }
}
