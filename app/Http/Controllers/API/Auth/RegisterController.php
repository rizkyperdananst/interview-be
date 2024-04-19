<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'phone_number' => 'required',
            'date_of_birth' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'required|min:5|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('users', $image->hashName(), 'public');

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'image' => $image->hashName(),
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Register Success',
            'data' => $user
        ]);
    }
}
