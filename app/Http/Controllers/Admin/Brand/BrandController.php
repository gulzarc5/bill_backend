<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Brand\BrandResource;
use App\Models\Brand;
use Validator;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    function list(){
        $brand = Brand::latest()->get();
        $response = [
            'status' => true,
            'message' => 'Brand List',
            'data' => BrandResource::collection($brand),
        ];
        return response()->json($response, 200);
    }
    function dropDown(){
        $brand = Brand::orderBy('name','asc')->whereStatus(1)->get(['id','name']);
        $response = [
            'status' => true,
            'message' => 'Brand List',
            'data' => $brand,
        ];
        return response()->json($response, 200);
    }

    function add(Request $request){
        $request->validate([
            'brand_id' => 'nullable|numeric|exists:brands,id',
            'name' => 'required|string|max:255',
        ]);

        //check for duplicate name
        $check_data = Brand::where(strtolower('name'),strtolower($request->name))->count();
        if ($check_data > 0) {
            $response = [
                'status' => false,
                'message' => 'Data Already Exists',
                'error_code' => true,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }

        if ($request->brand_id) {
            $brand = Brand::find($request->brand_id);
        } else {
            $brand = new Brand();
        }

        $brand->name = $request->name;
        $brand->save();
        $response = [
            'status' => true,
            'message' => 'Data Added Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $brand,
        ];
        return response()->json($response, 200);

    }


    function status(Request $request){
        $this->validate($request,[
            'brand_id' => 'required|numeric|exists:brands,id',
        ]);


        $brand = Brand::find($request->brand_id);

        $brand->status = $brand->status == 1 ? 2 : 1;
        $brand->save();
        $response = [
            'status' => true,
            'message' => 'Status Updated Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $brand,
        ];
        return response()->json($response, 200);

    }


}
