<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function loginSubmit(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Admin::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        if ($user->status == 2) {
            $response = [
                'status' => false,
                'message' => 'User is Currently Disabled by Admin',
                'error_code' => true,
            ];
            return response()->json($response, 200);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi '.$user->name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', 'user_type' => $user->type]);
    }

    function changePassword(Request $request){
        $this->validate($request,[
            'admin_id' => 'required|numeric|exists:admins,id',
            'current_password' => 'required|string|max:255',
            'new_password' => 'required|string|max:255',
        ]);

        $admins = Admin::find($request->admin_id);
        if (! Hash::check($request->current_password, $admins->password)) {
            $response = [
                'status' => false,
                'message' => 'Wrong Password',
                'error_code' => true,
            ];
            return response()->json($response, 200);
        }
        $admins->password = Hash::make($request->new_password);
        $admins->dcrypt_password = $request->new_password;
        $admins->save();
        $response = [
            'status' => true,
            'message' => 'Password Updated Successfully',
            'error_code' => false,
            'error_message' => null,
            'data' => $admins,
        ];
        return response()->json($response, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        $response =  [
            'status' => true,
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
        return response()->json($response, 200);
    }

    public function showImage($filename)
    {
        // Define the path to the directory where your images are stored
        $path = public_path('backend_images');

        // Build the full path to the image file
        $imagePath = $path . DIRECTORY_SEPARATOR . $filename;

        // Check if the file exists
        if (file_exists($imagePath)) {
            // Return the image with appropriate headers
            return response()->file($imagePath);
        } else {
            // Return a 404 Not Found response if the image does not exist
            return abort(404);
        }
    }
}
