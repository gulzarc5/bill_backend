<?php

namespace App\Http\Controllers\Admin\PriceMap;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PriceMap\PriceMapResource;
use App\Models\PriceMap;
use Illuminate\Http\Request;

class PriceMapController extends Controller
{
    function list(){
        $categories = PriceMap::latest()->get();
        $response = [
            'status' => true,
            'message' => 'PriceMap List',
            'data' => PriceMapResource::collection($categories),
        ];
        return response()->json($response, 200);
    }

    function add(Request $request){
        $request->validate([
            'price_map_id' => 'nullable|numeric|exists:price_maps,id',
            'material_id' => 'required|numeric|exists:materials,id',
            'glass_mm_id' => 'required|numeric|exists:glass_mms,id',
            'price' => 'required|numeric|gte:0',
            'bill_price' => 'required|numeric|gte:0',
        ]);


        if ($request->price_map_id) {
            $category = PriceMap::find($request->price_map_id);
        } else {
            // Fetch for duplicate
            $count = PriceMap::where('material_id',$request->material_id)->where('glass_mm_id',$request->glass_mm_id)->count();
            if ($count > 0) {
                $response = [
                    'status' => false,
                    'message' => 'Map Already Exists',
                    'error_code' => true,
                ];
                return response()->json($response, 200);
            }
            $category = new PriceMap();
        }

        $category->material_id = $request->material_id;
        $category->glass_mm_id = $request->glass_mm_id;
        $category->price = $request->price;
        $category->bill_price = $request->bill_price;
        $category->save();
        $response = [
            'status' => true,
            'message' => 'Data Added Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $category,
        ];
        return response()->json($response, 200);

    }

    function status(Request $request){
        $this->validate($request,[
            'price_map_id' => 'required|numeric|exists:price_maps,id',
        ]);

        $material = PriceMap::find($request->price_map_id);

        $material->status = $material->status == 1 ? 2 : 1;
        $material->save();
        $response = [
            'status' => true,
            'message' => 'Status Updated Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $material,
        ];
        return response()->json($response, 200);

    }
}
