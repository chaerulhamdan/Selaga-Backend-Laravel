<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\VenueResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VenueController extends Controller
{
    //
    public function index() {
        try {
            $venue = Venue::with('owner:id,name,email,phone', 'lapangans')->get();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Venue retrieved successfully.',
                'data' => VenueResource::collection($venue)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve venue.' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id) {
        try {
            $venueDetail = Venue::with('owner:id,name,email,phone' , 'lapangans')->findOrFail($id);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Venue detail retrieved successfully.',
                'data' => new VenueResource($venueDetail)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Venue not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve venue detail.'
            ], 500);
        }
    }

    public function store(Request $request) {
        try {
            $validate = $request->validate([
                'nameVenue' => 'required',
                'lokasiVenue' => 'required',
                'descVenue' => 'required',
                'fasilitasVenue' => 'required|array', // Validate fasilitasVenue as an array
                'price' => 'required',
                'rating' => 'required',
                'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg', // Validate each image
            ]);
    
            $uploadedImages = [];
    
            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $image) {
                    $fileName = $this->generateRandomString();
                    $extension = $image->extension();
                    $uploadedImage = $image->storeAs('image', $fileName . '.' . $extension);
                    $uploadedImages[] = $fileName . '.' . $extension;
                }
            }
    
            $requestData = $request->all();
            $requestData['image'] = implode(',', $uploadedImages); // Convert array to comma-separated string
            $requestData['fasilitasVenue'] = implode(',', $requestData['fasilitasVenue']); // Convert fasilitasVenue array to comma-separated string
            $requestData['mitraId'] = Auth::user()->id;
    
            $createVenue = Venue::create($requestData);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Venue created successfully.',
                'data' => new VenueResource($createVenue->loadMissing('owner:id,name,email,phone'))
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create Venue.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            $validate = $request->validate([
                'nameVenue' => 'required',
                'lokasiVenue' => 'required',
                'descVenue' => 'required',
                'fasilitasVenue' => 'required|array', // Validate fasilitasVenue as an array
                'price' => 'required',
                'rating' => 'required',
                'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg', // Validasi untuk setiap gambar
            ]);
    
            $updateVenue = Venue::findOrFail($id);
    
            $uploadedImages = [];
            
            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $image) {
                    $fileName = $this->generateRandomString();
                    $extension = $image->extension();
                    $uploadedImage = $image->storeAs('image', $fileName . '.' . $extension);
                    $uploadedImages[] = $fileName . '.' . $extension;
                }
                // Hapus gambar lama jika ada
                if (!empty($updateVenue->image)) {
                    $oldImages = explode(',', $updateVenue->image);
                    foreach ($oldImages as $oldImage) {
                        Storage::delete('image/' . $oldImage);
                    }
                }
            }
    
            $requestData = $request->all();
            
            // Convert fasilitasVenue array to comma-separated string
            if (isset($requestData['fasilitasVenue']) && is_array($requestData['fasilitasVenue'])) {
                $requestData['fasilitasVenue'] = implode(',', $requestData['fasilitasVenue']);
            }
            
            if (!empty($uploadedImages)) {
                $requestData['image'] = implode(',', $uploadedImages); // Mengubah array menjadi string dipisahkan koma
            }
            
            $updateVenue->update($requestData);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Venue updated successfully.',
                'data' => new VenueResource($updateVenue->loadMissing('owner:id,name,email,phone'))
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update Venue. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updaterating(Request $request, $id) {
        try {
            $validate = $request->validate([
                'rating' => 'required',
            ]);
    
            $updateVenue = Venue::findOrFail($id);
    
            $requestData = $request->all();
            
            $updateVenue->update($requestData);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Rating Venue updated successfully.',
                'data' => new VenueResource($updateVenue->loadMissing('owner:id,name,email,phone'))
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update Rating Venue. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatenoimage(Request $request, $id) {
        try {
            $validate = $request->validate([
                'nameVenue' => 'required',
                'lokasiVenue' => 'required',
                'descVenue' => 'required',
                'fasilitasVenue' => 'required|array', // Validate fasilitasVenue as an array
                'price' => 'required',
            ]);
    
            $updateVenue = Venue::findOrFail($id);
    
            $requestData = $request->all();
            
            // Convert fasilitasVenue array to comma-separated string
            if (isset($requestData['fasilitasVenue']) && is_array($requestData['fasilitasVenue'])) {
                $requestData['fasilitasVenue'] = implode(',', $requestData['fasilitasVenue']);
            }
            
            $updateVenue->update($requestData);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Venue updated successfully.',
                'data' => new VenueResource($updateVenue->loadMissing('owner:id,name,email,phone'))
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update Venue. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id){
        try {
            // Mencari venue berdasarkan ID, jika tidak ditemukan akan melempar ModelNotFoundException
            $deleteVenue = Venue::findOrFail($id);
            
            // Menghapus venue
            $deleteVenue->delete();
            
            // Memuat kembali data venue yang dihapus dengan relasi 'owner' dan mengembalikan resource
            return response()->json([
                'status' => 'success',
                'message' => 'Venue deleted successfully.',
                'data' => new VenueResource($deleteVenue->loadMissing('owner:id,name,email,phone'))
            ], 200);
        } catch (\ModelNotFoundException $e) {
            // Menangkap exception jika venue tidak ditemukan
            return response()->json([
                'status' => 'failed',
                'message' => 'Venue not found.'
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
