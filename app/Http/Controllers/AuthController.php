<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
    * Register a new user.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function register(Request $request)
    {

        // Validate the request
        $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'username' => 'required|string|max:255|unique:users',
        'password' => 'required|string|min:3|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Create the user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'username' => $request->username,
        'password' => Hash::make($request->password),
    ]);

    // Optionally, create a token for the user
    //$token = $user->createToken('auth_token')->plainTextToken;

    // Return a response
    return response()->json([
        'message' => 'User successfully registered',
        'user' => $user,
        //'token' => $token,
        ], 201);
    }

    /**
    * Authenticate a user.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function login(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
        'login' => 'required|string', // Change 'email' to 'login'
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }


    $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    // Attempt to authenticate the user
    if (Auth::attempt([$loginType => $request->login, 'password' => $request->password])) {
        // Authentication passed
        $user = Auth::user();
        //$token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Authenticated successfully',
            'user' => $user,
            //'token' => $token,
            ], 200);
        }

        // Authentication failed
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
