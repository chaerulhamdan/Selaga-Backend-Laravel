<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BookingResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    //
    public function index() {
        try {
            // Fetch the bookings with the related ordered and field information
            $booking = Booking::with('order:id,name,email,phone', 'timetable.lapangan.venue')->get();
    
            // Return a success response with the booking data
            return response()->json([
                'status' => 'success',
                'message' => 'Booking retrieved successfully.',
                'data' => BookingResource::collection($booking),
            ], 200);
        } catch (\Exception $e) {
            // Return an error response with the exception message
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve bookings: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id) {
        try {
            // Fetch the booking detail with the related ordered and field information
            $bookingDetail = Booking::with('order:id,name,email,phone', 'timetable.lapangan.venue')->findOrFail($id);
    
            // Return a success response with the booking detail
            return response()->json([
                'status' => 'success',
                'message' => 'Booking detail retrieved successfully.',
                'data' => new BookingResource($bookingDetail)
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Return an error response if the booking is not found
            return response()->json([
                'status' => 'error',
                'message' => 'Booking not found.'
            ], 404);
        } catch (\Exception $e) {
            // Return an error response for any other exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve booking detail.'
            ], 500);
        }
    }

    public function store(Request $request) {
        try {
            // Validate the request data
            $validate = $request->validate([
                'orderName' => 'required',
                'date' => 'required',
                'hours' => 'required',
                'payment' => 'required',
                'bookingId' => 'required',
                'confirmation' => 'required',
                'ratingStatus' => 'required'
            ]);
    
            // Add the authenticated user's ID to the request data
            $request['orderId'] = Auth::user()->id;
    
            // Handle the file upload if a file is provided
            $fileName = null;
            if ($request->file) {
                $fileName = $this->generateRandomString();
                $extension = $request->file->extension();
                Storage::putFileAs('payment', $request->file, $fileName . '.' . $extension);
                $request['image'] = $fileName . '.' . $extension;
            }
    
            // Create a new booking record
            $bookingCreate = Booking::create($request->all());
    
            // Load related data and return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully.',
                'data' => new BookingResource($bookingCreate->loadMissing('order:id,name,email,phone', 'timetable.lapangan.venue'))
            ], 201);
        } catch (ValidationException $e) {
            // Return an error response for validation failures
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            // Return an error response for any other exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create booking.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            // Validate the request data
            $validate = $request->validate([
                'confirmation' => 'required',
            ]);

            $bookingUpdate = Booking::findOrFail($id);
            $bookingUpdate->update($request->all());
    
            // Load related data and return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully.',
                'data' => new BookingResource($bookingUpdate->loadMissing('order:id,name,email,phone', 'timetable.lapangan.venue'))
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return an error response for validation failures
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Return an error response for any other exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create booking.',
                'errors' => $e->errors(),
            ], 500);
        }
    }

    public function updateratingstatus(Request $request, $id) {
        try {
            // Validate the request data
            $validate = $request->validate([
                'ratingStatus' => 'required'
            ]);

            $bookingUpdate = Booking::findOrFail($id);
            $bookingUpdate->update($request->all());
    
            // Load related data and return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully.',
                'data' => new BookingResource($bookingUpdate->loadMissing('order:id,name,email,phone', 'timetable.lapangan.venue'))
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return an error response for validation failures
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Return an error response for any other exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create booking.',
                'errors' => $e->errors(),
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
