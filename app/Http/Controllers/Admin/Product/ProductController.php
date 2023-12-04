<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Product\ProductResource;
use App\Models\PriceMap;
use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    function list(){
        $products = Product::with('priceMaps')->latest()->get();

        foreach ($products as $key => $value) {
            $priceData = PriceMap::where('material_id', $value->material_id)->where('glass_mm_id', $value->glass_mm_id)->first();
            if ($priceData) {
                // Calculate area in square feet considering glass thickness
                $value->area_sqfeet = ($value->height * $value->width) /89999;
                $value->total_price = $value->area_sqfeet*$priceData->price;
                $value->billing_price = $value->area_sqfeet*$priceData->bill_price;
            }
        }
        $response = [
            'status' => true,
            'message' => 'Product List',
            'data' => ProductResource::collection($products),
        ];
        return response()->json($response, 200);
    }
    function dropDown(){
        $products = Product::orderBy('name','asc')->whereStatus(1)->get(['id','name']);
        $response = [
            'status' => true,
            'message' => 'Product List',
            'data' => $products,
        ];
        return response()->json($response, 200);
    }

    function add(Request $request){
        $request->validate([
            'product_id' => 'nullable|numeric|exists:products,id',
            'product_name' => 'required|string|max:255',
            'hsn_code' => 'required|string|max:255',
            'category_id' => 'required|numeric|exists:categories,id',
            'material_id' => 'required|numeric|exists:materials,id',
            'height' => 'required|numeric|gt:0',
            'width' => 'required|numeric|gt:0',
            'glass_mm_id' => 'required|numeric|exists:glass_mms,id',
            'brand_id' => 'nullable|numeric|exists:brands,id',
            'desc' => 'nullable|string',
            'location' => 'nullable|string',
            'accessories' => 'nullable|string',
            'image' => 'required_without:product_id|nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);


        if ($request->product_id) {
            $product = Product::find($request->product_id);
        } else {
            $product = new Product();
        }

        $product->name = $request->product_name;
        $product->hsn_code = $request->hsn_code;
        $product->category_id = $request->category_id;
        $product->material_id = $request->material_id;
        $product->height = $request->height;
        $product->width = $request->width;
        $product->glass_mm_id = $request->glass_mm_id;
        $product->brand_id = $request->brand_id;
        $product->description = $request->desc;
        $product->location = $request->location;
        $product->Accesories = $request->accessories;
        if ($request->hasFile('image')) {
            $old_image = $product->image;
            $product->image = ImageService::save($request->file('image'));
            ImageService::delete($old_image);
        }
        $product->save();
        $product->item_code = "KE".rand(11111,99999)."".$product->id;
        $product->save();
        $response = [
            'status' => true,
            'message' => 'Data Added Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $product,
        ];
        return response()->json($response, 200);

    }

    function status(Request $request){
        $this->validate($request,[
            'product_id' => 'nullable|numeric|exists:products,id',
        ]);
        $product = Product::find($request->product_id);

        $product->status = $product->status == 1 ? 2 : 1;
        $product->save();
        $response = [
            'status' => true,
            'message' => 'Status Updated Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $product,
        ];
        return response()->json($response, 200);

    }

    function fetch_price(Request $request){
        $this->validate($request,[
            'material_id' => 'required|numeric|exists:materials,id',
            'glass_mm_id' => 'required|numeric|exists:glass_mms,id',
        ]);

        if(!empty($request->material_id) && !empty($request->glass_mm_id)){
            $fetch_price = PriceMap::where('material_id',$request->material_id)->where('glass_mm_id',$request->glass_mm_id)->whereStatus(1)->first();
            if ($fetch_price) {
                    $response = [
                        'status' => true,
                        'map_status' => true,
                        'error_code' => false,
                        'error_message' => null,
                        'price' => $fetch_price->price,
                        'billing_price' => $fetch_price->bill_price,
                    ];
                    return response()->json($response, 200);
            }else{
                $response = [
                    'status' => true,
                    'error_code' => true,
                    'error_message' => null,
                    'message' => 'Price Not Maped',
                    'price' => 0.00,
                    'billing_price' => 0.00,
                ];
                return response()->json($response, 200);
            }
        }else{
            $response = [
                'status' => false,
                'error_code' => true,
                'error_message' => null,
                'price' => null,
            ];
            return response()->json($response, 200);
        }
    }
}
