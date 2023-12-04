<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Category\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    function list(){
        $categories = Category::latest()->get();
        $response = [
            'status' => true,
            'message' => 'Category List',
            'data' => CategoryResource::collection($categories),
        ];
        return response()->json($response, 200);
    }
    function dropDown(){
        $categories = Category::orderBy('name','asc')->whereStatus(1)->get(['id','name']);
        $response = [
            'status' => true,
            'message' => 'Category List',
            'data' => $categories,
        ];
        return response()->json($response, 200);
    }

    function add(Request $request){
        $request->validate([
            'category_id' => 'nullable|numeric|exists:categories,id',
            'category_name' => 'required|string|max:255',
        ]);

        //check for duplicate name
        $check_data = Category::where(strtolower('name'),strtolower($request->category_name))->count();
        if ($check_data > 0) {
            $response = [
                'status' => false,
                'message' => 'Data Already Exists',
                'error_code' => true,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }

        if ($request->category_id) {
            $category = Category::find($request->category_id);
        } else {
            $category = new Category();
        }

        $category->name = $request->category_name;
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
            'category_id' => 'required|numeric|exists:categories,id',
        ]);

        $category = Category::find($request->category_id);

        $category->status = $category->status == 1 ? 2 : 1;
        $category->save();
        $response = [
            'status' => true,
            'message' => 'Status Updated Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $category,
        ];
        return response()->json($response, 200);

    }


}
