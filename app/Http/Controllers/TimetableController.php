<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Http\Resources\TimetableResource;

class TimetableController extends Controller
{
    //
    public function index() {
        try {
            $timetable = Timetable::with('lapangan')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Lapangan retrieved successfully.',
                'data' => TimetableResource::collection($timetable)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve timetable data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id) {
        try {
            $timetableDetail = Timetable::with('lapangan')->findOrFail($id);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Timetable detail retrieved successfully.',
                'data' => new TimetableResource($timetableDetail)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Timetable not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve timetable detail.'
            ], 500);
        }
    }

    public function store(Request $request) {
        try {
            $validate = $request->validate([
                'nameVenue' => 'required',
                'nameLapangan' => 'required',
                'days' => 'required',
                'availableHour' => 'required',
                'unavailableHour' => 'required',
                'lapanganId' => 'required',
            ]);
    
            $timetableCreate = Timetable::create($request->all());
    
            return response()->json([
                'status' => 'success',
                'message' => 'Timetable created successfully.',
                'data' => new TimetableResource($timetableCreate->loadMissing('lapangan'))
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
                'message' => 'Failed to create timetable.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            $validate = $request->validate([
                'nameVenue' => 'required',
                'nameLapangan' => 'required',
                'days' => 'required',
                'availableHour' => 'required',
                'unavailableHour' => 'required',
                'lapanganId' => 'required',
            ]);
    
            $timetableUpdate = Timetable::findOrFail($id);
            $timetableUpdate->update($request->all());
    
            return response()->json([
                'status' => 'success',
                'message' => 'Timetable updated successfully.',
                'data' => new TimetableResource($timetableUpdate->loadMissing('lapangan'))
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Timetable not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update timetable.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id){
        try {
            // Mencari venue berdasarkan ID, jika tidak ditemukan akan melempar ModelNotFoundException
            $timetableDelete = Timetable::findOrFail($id);
            
            // Menghapus venue
            $timetableDelete->delete();
            
            // Memuat kembali data venue yang dihapus dengan relasi 'owner' dan mengembalikan resource
            return response()->json([
                'status' => 'success',
                'message' => 'Timetable deleted successfully.',
                'data' => new LapanganResource($timetableDelete->loadMissing('lapangan'))
            ], 200);
        } catch (\ModelNotFoundException $e) {
            // Menangkap exception jika venue tidak ditemukan
            return response()->json([
                'status' => 'failed',
                'message' => 'Timetable not found.'
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
}
