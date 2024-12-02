<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required',
            'STATUS' => 'required'
        ]);
    
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'STATUS' => $request->STATUS
            ]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'register berhasil',
                'token' => $user->createToken('user login')->plainTextToken
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'register gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email tidak ditemukan.'
            ], 401);
        }
    
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password salah.'
            ], 401);
        }
    
        $token = $user->createToken('user login')->plainTextToken;
    
        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'token' => $token
        ], 200);
    }

    public function logout(Request $request) {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Logout berhasil'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Logout gagal', 'error' => $e->getMessage()], 500);
        }
    }

    public function profile(Request $request) {
        $user = Auth::user();
    
        if ($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'Profile retrieved successfully.',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve profile.'
            ], 401);
        }
    }

}
