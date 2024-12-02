<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthmitraController extends Controller
{
    //
     //
     public function register(Request $request) {
        $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|unique:users,phone',
                'password' => 'required',
            ]);
    
        try {
            $user = Mitra::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
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

     public function login(Request $request)  {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $mitra = Mitra::where('email', $request->email)->first();
    
        if (!$mitra) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email tidak ditemukan.'
            ], 401);
        }
    
        if (!Hash::check($request->password, $mitra->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password salah.'
            ], 401);
        }
    
        $token = $mitra->createToken('mitra login')->plainTextToken;
    
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

    public function profile(Request $request)  {
        $mitra = Auth::user();
    
        if ($mitra) {
            return response()->json([
                'status' => 'success',
                'message' => 'Profile retrieved successfully.',
                'data' => $mitra
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve profile.'
            ], 401);
        }
    }
}
