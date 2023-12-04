<?php

namespace App\Http\Controllers\Admin\GlassMM;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\GlassMM\GlassMMResource;
use App\Models\Glass_mm;
use App\Models\PriceMap;
use Validator;
use Illuminate\Http\Request;

class GlassMMController extends Controller
{
    function list(){
        $glass_mm = Glass_mm::latest()->get();
        $response = [
            'status' => true,
            'message' => 'Glass MM List',
            'data' => GlassMMResource::collection($glass_mm),
        ];
        return response()->json($response, 200);
    }
    function dropDown(){
        $glass_mm = Glass_mm::orderBy('name','asc')->whereStatus(1)->get(['id','name']);
        $response = [
            'status' => true,
            'message' => 'Glass MM List',
            'data' => $glass_mm,
        ];
        return response()->json($response, 200);
    }

    function productDropDown(){
        $glass_mm = Glass_mm::orderBy('name','asc')
            ->whereExists(
                function($query){
                $query->from('price_maps')
                    ->whereRaw('price_maps.glass_mm_id = glass_mms.id');
                })
            ->whereStatus(1)->get(['id','name']);
        $response = [
            'status' => true,
            'message' => 'Glass MM List',
            'data' => $glass_mm,
        ];
        return response()->json($response, 200);
    }

    function add(Request $request){
        $request->validate([
            'glass_mms_id' => 'nullable|numeric|exists:glass_mms,id',
            'name' => 'required|string|max:255',
        ]);

        //check for duplicate name
        $check_data = Glass_mm::where(strtolower('name'),strtolower($request->name))->count();
        if ($check_data > 0) {
            $response = [
                'status' => false,
                'message' => 'Data Already Exists',
                'error_code' => true,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }

        if ($request->glass_mms_id) {
            $glass_mm = Glass_mm::find($request->glass_mms_id);
        } else {
            $glass_mm = new Glass_mm();
        }

        $glass_mm->name = $request->name;
        $glass_mm->save();
        $response = [
            'status' => true,
            'message' => 'Data Added Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $glass_mm,
        ];
        return response()->json($response, 200);

    }


    function status(Request $request){
        $this->validate($request,[
            'glass_mms_id' => 'required|numeric|exists:glass_mms,id',
        ]);

        $glass_mm = Glass_mm::find($request->glass_mms_id);

        $glass_mm->status = $glass_mm->status == 1 ? 2 : 1;
        $glass_mm->save();
        $response = [
            'status' => true,
            'message' => 'Status Updated Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $glass_mm,
        ];
        return response()->json($response, 200);

    }
}
