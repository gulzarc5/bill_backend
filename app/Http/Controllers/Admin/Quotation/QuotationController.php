<?php

namespace App\Http\Controllers\Admin\Quotation;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Quotation\QuotationResource;
use App\Models\Buyer;
use App\Models\PriceMap;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    function list(Request $request) {
        if ($request->user()->type == 1) {
            $quotations = Quotation::latest()->get();
        }else{
            $quotations = Quotation::where('added_by',$request->user()->id)->latest()->get();
        }
        $response = [
            'status' => true,
            'message' => 'Quotation List',
            'data' => QuotationResource::collection($quotations),
        ];
        return response()->json($response, 200);
    }


    function create(Request $request) {

        $this->validate($request,[
            'mobile' => 'required|numeric|digits:10',
            'products' => 'required|array|min:1',
        ]);
        $client = Buyer::where('mobile', $request->input('mobile'))->firstOrFail();
        $products = $request->input('products');

        $productValidationError = true;
        foreach($products as $product){

            if (isset($product['selectedProduct']) && !empty($product['selectedProduct']) && isset($product['quantity']) && !empty($product['quantity']) && isset($product['height']) && !empty($product['height']) && isset($product['width']) && !empty($product['width']) && isset($product['selected_glass_mm']) && !empty($product['selected_glass_mm']) && isset($product['glass_mm']) && !empty($product['glass_mm']) && ($product['height'] > 0) && ($product['width'] > 0) && ($product['quantity'] > 0)) {
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

        $quotation = new Quotation();
        $quotation->client_id = $client->id;
        $quotation->added_by = $request->user()->id;
        if ($quotation->save()) {
            // return $request->totalIgst;
            $this->quotaionDetialSave($products,$quotation,$request, $client);
            $response = [
                'status' => true,
                'message' => 'Quotation Created Successfully',
                'quotation_id' => $quotation->id,
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

    private function quotaionDetialSave($products,Quotation $quotation, Request $request, Buyer $client){
        $total_sq_feet = 0;
        $total_amount = 0;
        foreach($products as $product){
            if (isset($product['selectedProduct']) && !empty($product['selectedProduct']) && isset($product['quantity']) && !empty($product['quantity']) && isset($product['height']) && !empty($product['height']) && isset($product['width']) && !empty($product['width']) && isset($product['selected_glass_mm']) && !empty($product['selected_glass_mm']) && isset($product['glass_mm']) && !empty($product['glass_mm']) && ($product['height'] > 0) && ($product['width'] > 0) && ($product['quantity'] > 0)) {
                $quotationDetials = new QuotationDetails();
                $productData = Product::find($product['selectedProduct']);
                $quotationDetials->quotation_id = $quotation->id;
                $quotationDetials->product_id = $productData->id;
                $quotationDetials->product_name = $productData->name;

                $quotationDetials->category_id = $productData->category_id;
                $quotationDetials->category_name = $productData->category->name;

                $quotationDetials->glass_mm_id = $product['selected_glass_mm'];
                $quotationDetials->glass_mm = $product['glass_mm']['glass_mm'];

                $quotationDetials->material_id = $productData->material_id;
                $quotationDetials->material_name = $productData->material->name;

                $quotationDetials->height = $product['height'];
                $quotationDetials->width = $product['width'];

                $quotationDetials->per_sqfeet_amount = $product['glass_mm']['price'];
                $quotationDetials->quantity = $product['quantity'];

                $quotationDetials->total_sq_feet = $product['totalSqFeet'];
                $quotationDetials->save();

                $total_amount = ($total_amount + $product['totalAmount']);
            }
        }

        if (strtolower($client->state) != 'karnataka') {
            $quotation->igst = $request->totalIgst;
            $quotation->cgst = 0.00;
            $quotation->sgst = 0.00;
        } else {
            $quotation->cgst = $request->totalCgst;
            $quotation->sgst = $request->totalSgst;
            $quotation->igst = 0.00;
        }

        $quotation->total_sq_feet = $request->totalSqFeet;
        $quotation->amount = $total_amount;
        $quotation->total_amount = $quotation->amount + $quotation->igst + $quotation->cgst + $quotation->sgst;

        if ($quotation->save()) {
            return true;
        } else {
            return false;
        }

    }

    private function priceFetch($product){
        $data = [];
        $priceData = PriceMap::where('material_id', $product->material_id)->where('glass_mm_id', $product->glass_mm_id)->first();
        if ($priceData) {
            // Calculate area in square feet considering glass thickness
            $data['area_sqfeet'] = ($product->height * $product->width)/89999;
            $data['total_price'] = $data['area_sqfeet']*$priceData->price;
            $data['per_sq_feet'] = $priceData->price;
        }
        return $data;
    }

    public function fetch(Request $request){
        $request->validate([
            'quotation_id' => 'required|numeric|exists:quotations,id',
        ]);
        $quotation = Quotation::find($request->quotation_id);
        $response = [
            'status' => true,
            'message' => 'Quotation Details',
            'data' => QuotationResource::make($quotation),
        ];
        return response()->json($response, 200);

    }
}
