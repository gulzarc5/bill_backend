<?php

namespace App\Http\Controllers\Admin\Material;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Material\MaterialResource;
use App\Models\Material;
use Illuminate\Http\Request;
use Validator;

class MaterialController extends Controller
{
    function list(){
        $categories = Material::latest()->get();
        $response = [
            'status' => true,
            'message' => 'Material List',
            'data' => MaterialResource::collection($categories),
        ];
        return response()->json($response, 200);
    }
    function dropDown(){
        $categories = Material::orderBy('name','asc')->whereStatus(1)->get(['id','name']);
        $response = [
            'status' => true,
            'message' => 'Material List',
            'data' => $categories,
        ];
        return response()->json($response, 200);
    }
    function productDropDown(){
        $categories = Material::orderBy('name','asc')
            ->whereStatus(1)
            ->whereExists(
                function($query){
                $query->from('price_maps')
                    ->whereRaw('price_maps.material_id = materials.id');
                })
            ->get(['id','name']);
        $response = [
            'status' => true,
            'message' => 'Material List',
            'data' => $categories,
        ];
        return response()->json($response, 200);
    }

    function add(Request $request){
        $request->validate([
            'material_id' => 'nullable|numeric|exists:materials,id',
            'material_name' => 'required|string|max:255',
        ]);

        //check for duplicate name
        $check_data = Material::where(strtolower('name'),strtolower($request->material_name))->count();
        if ($check_data > 0) {
            $response = [
                'status' => false,
                'message' => 'Data Already Exists',
                'error_code' => true,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }


        if ($request->material_id) {
            $category = Material::find($request->material_id);
        } else {
            $category = new Material();
        }

        $category->name = $request->material_name;
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
            'material_id' => 'required|numeric|exists:materials,id',
        ]);

        $material = Material::find($request->material_id);

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
