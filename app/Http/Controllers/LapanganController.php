<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\LapanganResource;

class LapanganController extends Controller
{
    //
    public function index() {
        try {
            $lapangan = Lapangan::with('venue')->get();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Lapangan retrieved successfully.',
                'data' => LapanganResource::collection($lapangan)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve lapangan.' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id) {
        try {
            $lapanganDetail = Lapangan::with('venue')->findOrFail($id);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Lapangan detail retrieved successfully.',
                'data' => new LapanganResource($lapanganDetail)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lapangan not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve lapangan detail.'
            ], 500);
        }
    }

    public function store(Request $request) {
        try {
            $validate = $request->validate([
                'nameLapangan' => 'required',
                'days' => 'required',
                'hour' => 'required',
                'venueId' => 'required',
            ]);
        
            $lapanganCreate = Lapangan::create($request->all());
        
            return response()->json([
                'status' => 'success',
                'message' => 'Lapangan created successfully.',
                'data' => new LapanganResource($lapanganCreate->loadMissing('venue'))
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create lapangan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            $validate = $request->validate([
                'nameLapangan' => 'required',
                'days' => 'required',
                'hour' => 'required',
            ]);
    
            $lapanganUpdate = Lapangan::findOrFail($id);
    
    
            $requestData = $request->all();
            $lapanganUpdate->update($requestData);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Lapangan updated successfully.',
                'data' => new LapanganResource($lapanganUpdate->loadMissing('venue'))
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update lapangan. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id){
        try {
            // Mencari venue berdasarkan ID, jika tidak ditemukan akan melempar ModelNotFoundException
            $deleteLapangan = Lapangan::findOrFail($id);
            
            // Menghapus venue
            $deleteLapangan->delete();
            
            // Memuat kembali data venue yang dihapus dengan relasi 'owner' dan mengembalikan resource
            return response()->json([
                'status' => 'success',
                'message' => 'Lapangan deleted successfully.',
                'data' => new LapanganResource($deleteLapangan->loadMissing('venue'))
            ], 200);
        } catch (\ModelNotFoundException $e) {
            // Menangkap exception jika venue tidak ditemukan
            return response()->json([
                'status' => 'failed',
                'message' => 'Lapangan not found.'
            ], 404);
        } catch (\Exception $e) {
            // Menangkap exception umum lainnya
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while deleting the venue.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
