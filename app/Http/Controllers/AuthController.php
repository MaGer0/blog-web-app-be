<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'nickname' => 'required|string|max:20',
            'email' => 'email|required|unique:users,email',
            'password' => 'required|min:8'
        ], [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must be less than 50 characters',
            'nickname.required' => 'Nickname is required',
            'nickname.string' => 'Nickname must be a string',
            'nickname.max' => 'Nickname must be less than 20 characters',
            'email.unique' => 'This email is already registered',
            'email.email' => 'Invalid email format',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return response()->json([
            'message' => 'User Created Successfully'
        ]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ], [
            'email.email' => 'Invavid email format',
            'email.required' => 'Email is required'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'Login Gagal'
            ]);
        }
        return $user->createToken('Blog Web API Access Token')->plainTextToken;
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout Successfully'
        ]);
    }
}
