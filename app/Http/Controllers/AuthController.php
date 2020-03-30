<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register']]);
    }

    /**
     * Store a new user
     *
     * @param   Request $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        try {
            $user = new User;
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            return response()->json([
                'message' => 'User Registered Successfully',
                'user' => $user
            ], 201);
        } catch (\Expection $e) {
            return response()->json(['message' => 'User Registration Failed'], 500);
        }
    }
}
