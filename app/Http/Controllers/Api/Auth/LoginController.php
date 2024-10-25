<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $credentials['email'])->firstOrFail();

        if(Hash::check($credentials['password'], $user->password)){
            // $token = $user->createToken('auth_token')->plainTextToken;
            // return response()->json([
            //     'token' => $token,
            //     'token_type' => 'Bearer'
            // ]);
            return UserResource::make($user);
        }else{
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }
}
