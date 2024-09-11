<?php

namespace App\Http\Controllers\API;

namespace App\Models\User;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PharIo\Manifest\Email;

class AuthController extends Controller
{
    //


    public function signup(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all()

            ], 401);
        }


        $user = User::create([
            'name' => $request->name,
            'name' => $request->email,
            'name' => $request->password,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User create Successfully',
            'user' => $user,

        ], 200);
    }
    public function login(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication Fails',
                'errors' => $validateUser->errors()->all()

            ], 404);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $userAuth = Auth::user();
            return response()->json([
                'status' => true,
                'message' => 'User Login Successfully',
                'token' => $userAuth->creatToken("Api Token")->plainTextToken,
                'token_type' => 'bearer'
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Email and Password dose not matched.',

            ], 401);
        }
    }
    public function logout(Request $request) {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json([
            'status' => true,
            'user' => $user,
            'message' => 'You Logged Out Successfully',
            
        ], 200);
    }
}
