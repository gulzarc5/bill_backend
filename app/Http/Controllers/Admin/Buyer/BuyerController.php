<?php

namespace App\Http\Controllers\Admin\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Buyer\BuyerResource;
use App\Models\Buyer;
use Validator;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    function list(){
        $buyers = Buyer::latest()->get();
        $response = [
            'status' => true,
            'message' => 'Buyer List',
            'data' => BuyerResource::collection($buyers),
        ];
        return response()->json($response, 200);
    }
    function dropDown(){
        $buyers = Buyer::orderBy('name','asc')->whereStatus(1)->get(['id','name']);
        $response = [
            'status' => true,
            'message' => 'Buyer List',
            'data' => $buyers,
        ];
        return response()->json($response, 200);
    }
    function fetch(Request $request){
        $buyers = Buyer::where('mobile',$request->mobile)->first();
        if ($buyers) {
            $response = [
                'status' => true,
                'message' => 'Buyer List',
                'data' => $buyers,
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'No Data Found',
            ];
        }

        return response()->json($response, 200);
    }

    function add(Request $request){
        $this->validate($request,[
            'buyer_id' => 'nullable|numeric|exists:buyers,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|max:50|email',
            'mobile' => 'required|numeric|digits:10|unique:buyers,mobile,'.$request->input('buyer_id'),
            'gst' => 'required|string|max:20',
            'state' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'pin' => 'nullable|numeric|digits:6',
            'address' => 'nullable|string',
        ]);

        if ($request->buyer_id) {
            $buyer = Buyer::find($request->buyer_id);
        } else {
            $buyer = new Buyer();
        }

        $buyer->name = $request->name;
        $buyer->email = $request->email;
        $buyer->mobile = $request->mobile;
        $buyer->state = $request->state;
        $buyer->city = $request->city;
        $buyer->pin = $request->pin;
        $buyer->gst_number = $request->gst;
        $buyer->address = $request->address;
        $buyer->save();
        $response = [
            'status' => true,
            'message' => 'Data Added Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $buyer,
        ];
        return response()->json($response, 200);

    }

    function status(Request $request){
        $this->validate($request,[
            'buyer_id' => 'nullable|numeric|exists:buyers,id',
        ]);

        $buyer = Buyer::find($request->buyer_id);

        $buyer->status = $buyer->status == 1 ? 2 : 1;
        $buyer->save();
        $response = [
            'status' => true,
            'message' => 'Status Updated Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $buyer,
        ];
        return response()->json($response, 200);

    }
}
