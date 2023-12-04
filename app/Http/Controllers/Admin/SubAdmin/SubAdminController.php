<?php

namespace App\Http\Controllers\Admin\SubAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SubAdmin\SubAdminResource;
use App\Models\Admin;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SubAdminController extends Controller
{
    function list(Request $request){
        // dd($request->user());
        $sub_admins = Admin::where('type',2)->latest()->get();
        $response = [
            'status' => true,
            'message' => 'Sub Admin List',
            'data' => SubAdminResource::collection($sub_admins),
        ];
        return response()->json($response, 200);
    }

    function add(Request $request){
        $this->validate($request,[
            'sub_admin_id' => 'nullable|numeric|exists:admins,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'mobile' => 'required|numeric|digits:10|unique:admins,mobile,'.$request->input('sub_admin_id'),
            'password' => 'required|string|max:255',
        ]);


        if ($request->sub_admin_id) {
            $sub_admins = Admin::find($request->sub_admin_id);
        } else {
            $sub_admins = new Admin();
        }

        $sub_admins->name = $request->name;
        $sub_admins->email = $request->email;
        $sub_admins->mobile = $request->mobile;
        $sub_admins->password = Hash::make($request->password);
        $sub_admins->dcrypt_password = $request->password;
        $sub_admins->type = 2;
        $sub_admins->save();
        $response = [
            'status' => true,
            'message' => 'Data Added Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $sub_admins,
        ];
        return response()->json($response, 200);
        
    }


    function changePassword(Request $request){
        $this->validate($request,[
            'sub_admin_id' => 'required|numeric|exists:admins,id',
            'current_password' => 'required|string|max:255',
            'new_password' => 'required|string|max:255',
        ]);


        $sub_admins = Admin::find($request->sub_admin_id);
        if (! Hash::check($request->current_password, $sub_admins->password)) {
            $response = [
                'status' => false,
                'message' => 'Wrong Password',
                'error_code' => true,
            ];
            return response()->json($response, 200);
        }
        $sub_admins->password = Hash::make($request->new_password);
        $sub_admins->dcrypt_password = $request->new_password;
        $sub_admins->save();
        $response = [
            'status' => true,
            'message' => 'Password Updated Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $sub_admins,
        ];
        return response()->json($response, 200);
        
    }


    function status(Request $request){
        $this->validate($request,[
            'sub_admin_id' => 'required|numeric|exists:admins,id',
        ]);


        $sub_admins = Admin::find($request->sub_admin_id);
        $sub_admins->status = $sub_admins->status == 1 ? 2 : 1;
        $sub_admins->save();
        $response = [
            'status' => true,
            'message' => 'Status Updated Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $sub_admins,
        ];
        return response()->json($response, 200);
        
    }
}
