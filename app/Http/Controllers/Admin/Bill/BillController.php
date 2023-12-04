<?php

namespace App\Http\Controllers\Admin\Bill;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminBillCreateRequest;
use App\Http\Resources\Admin\Bill\BillResource;
use App\Models\Bill;
use App\Models\BillDetails;
use App\Models\Buyer;
use App\Models\PriceMap;
use App\Models\Product;
use App\Services\WalletService;
use Illuminate\Http\Request;

class BillController extends Controller
{
    function list(Request $request) {
        if ($request->user()->type == 1) {
            $bills = Bill::latest()->get();
        } else {
            $bills = Bill::where('added_by',$request->user()->id)->latest()->get();
        }
        $response = [
            'status' => true,
            'message' => 'Bills List',
            'data' => BillResource::collection($bills),
        ];
        return response()->json($response, 200);
    }

    function create(AdminBillCreateRequest $request) {
        $client = Buyer::where('mobile', $request->input('mobile'))->firstOrFail();
        $products = $request->input('products');

        $productValidationError = true;
        foreach($products as $product){
            if (isset($product['selectedProduct']) && !empty($product['selectedProduct']) && isset($product['quantity']) && !empty($product['quantity']) && isset($product['height']) && !empty($product['height']) && isset($product['width']) && !empty($product['width']) && isset($product['selected_glass_mm']) && !empty($product['selected_glass_mm']) && isset($product['glass_mm']) && !empty($product['glass_mm']) && ($product['height'] > 0) && ($product['width'] > 0)  && ($product['quantity'] > 0)) {
                $productValidationError = false;
                break;
            }
        }

        if ($productValidationError) {
            $response = [
                'status' => false,
                'message' => 'Please Add atleast one product and quantity in the form',
            ];
            return response()->json($response, 200);
        }

        $bill = new Bill();
        $bill->client_id = $client->id;
        $bill->added_by = $request->user()->id;
        do {
            $ack_no = rand(1111111111,9999999999);
            $count = Bill::where('ack_no',$ack_no)->count();
            # code...
        } while ($count > 0);
        do {
            $doc_no = rand(1111,9999);
            $count = Bill::where('doc_no',$doc_no)->count();
            # code...
        } while ($count > 0);

        $bill->ack_no = $ack_no;
        $bill->doc_no = $doc_no;
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $irn_no = '';

        for ($i = 0; $i < 16; $i++) {
            $irn_no .= $characters[rand(0, strlen($characters) - 1)];
        }
        $bill->irn_no = $irn_no;
        $bill->is_same = $request->is_same;
        $bill->recipient_name = $request->is_same == 2 ? $request->recipient_name : null;
        $bill->recipient_mobile = $request->is_same == 2 ? $request->recipient_mobile : null;
        $bill->e_way_bill_no = $request->e_way_bill_no;
        $bill->e_way_bill_rate = $request->eWayBillDate;
        $bill->e_way_bill_valid_date = $request->e_way_bill_valid_date;
        $bill->supply_type = $request->supply_type;
        $bill->discount = $request->discount;
        $bill->cash_recieved = $request->cash_recieved;
        $bill->round_off_amount = $request->rundOfAmount;


        if ($bill->save()) {
            $this->billDetialSave($products,$bill,$client, $request);
            $response = [
                'status' => true,
                'message' => 'Bill Created Successfully',
                'bill_id' => $bill->id,
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try again',
            ];
            return response()->json($response, 200);
        }



    }

    private function billDetialSave($products,Bill $bill, Buyer $buyer, Request $request){
        $total_sq_feet = 0;
        $total_amount = 0;
        foreach($products as $product){
            if (isset($product['selectedProduct']) && !empty($product['selectedProduct']) && isset($product['quantity']) && !empty($product['quantity'])) {
                $billDetials = new BillDetails();
                $productData = Product::find($product['selectedProduct']);
                $billDetials->bill_id = $bill->id;
                $billDetials->product_id = $productData->id;
                $billDetials->product_name = $productData->name;

                $billDetials->category_id = $productData->category_id;
                $billDetials->category_name = $productData->category->name;

                $billDetials->glass_mm_id = $product['selected_glass_mm'];
                $billDetials->glass_mm = $product['glass_mm']['glass_mm'];

                $billDetials->material_id = $productData->material_id;
                $billDetials->material_name = $productData->material->name;

                $billDetials->height = $product['height'];
                $billDetials->width = $product['width'];

                $billDetials->per_sqfeet_amount = $product['glass_mm']['bill_price'];
                $billDetials->quantity = $product['quantity'];

                $billDetials->total_sq_feet =$product['totalSqFeet'];
                $billDetials->save();

                $total_amount = ($total_amount +$product['totalAmount']);
            }
        }

        $bill->total_sq_feet = $request->totalSqFeet;

        $bill->amount = $total_amount;
        $bill->transport_charge = $request->transportCharge;
        if (strtolower($buyer->state) != 'karnataka') {
            $bill->client_igst = $request->totalIgst;
            $bill->cgst = 0.00;
            $bill->sgst = 0.00;
        } else {
            $bill->cgst = $request->totalCgst;
            $bill->sgst = $request->totalSgst;
            $bill->client_igst = 0.00;
        }

        $bill->total_amount = ($bill->amount + $bill->cgst + $bill->sgst + $bill->client_igst + $bill->transport_charge) - $bill->discount;
        $bill->outstanding_amount = (($bill->total_amount+$bill->round_off_amount) - ($bill->cash_recieved));
        $bill->save();

        $wallet_service = new WalletService();
        $wallet_service->beginWalletTransaction($bill->id,$bill->client_id,$bill->total_amount + $bill->round_off_amount,1,"Total Bill Amount");


        $wallet_service = new WalletService();
        $wallet_service->beginWalletTransaction($bill->id,$bill->client_id,$bill->cash_recieved,2,"Bill Amount Recieved");

        return true;
    }

    private function priceFetch($product){
        $data = [];
        $priceData = PriceMap::where('material_id', $product->material_id)->where('glass_mm_id', $product->glass_mm_id)->first();
        if ($priceData) {
            $data['area_sqfeet'] = (($product->height * $product->width) / 89999);
            $data['total_price'] = $data['area_sqfeet']*$priceData->bill_price;
            $data['per_sq_feet'] = $priceData->bill_price;
        }
        return $data;
    }

    public function fetch(Request $request){
        $request->validate([
            'bill_id' => 'required|numeric|exists:bills,id',
        ]);
        $bill = Bill::find($request->bill_id);
        $client = Buyer::where('id', $bill->client_id)->first();
        if (strtolower($client->state) != 'karnataka') {
            $same_state = 1; // 1: no 2: yes
        } else {
            $same_state = 2;
        }
        $response = [
            'status' => true,
            'message' => 'Bill Details',
            'same_state' => $same_state,
            'data' => BillResource::make($bill),
        ];
        return response()->json($response, 200);

    }
}
