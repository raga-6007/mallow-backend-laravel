<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Login function
    public function login(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Check if the user exists
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Generate a token for the user
        $token = $user->createToken('YourAppName')->plainTextToken; // For Sanctum


        return response()->json([
            'message' => 'Login successful',
            'name'=>$user->name,
            'token' => $token,
        ]);
    }

    // Register function (optional)
    public function register(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Generate a token for the user
        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'token' => $token,
        ]);
    }
}
