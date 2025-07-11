<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Get the current teacher's profile
     */
    public function show()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->with('user')->first();
        
        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found'], 404);
        }
        
        $profile = [
            'id' => $teacher->id,
            'name' => $teacher->user->name,
            'email' => $teacher->user->email,
            'phone' => $teacher->user->phone,
            'employee_id' => $teacher->employee_id,
            'department' => $teacher->department,
            'specialization' => $teacher->specialization,
            'hire_date' => $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : null,
            'address' => $teacher->address,
            'status' => $teacher->status,
            'profile_image' => $teacher->profile_image,
            'has_profile_image' => $teacher->hasProfileImage(),
            'created_at' => $teacher->created_at,
            'updated_at' => $teacher->updated_at,
        ];
        
        return response()->json($profile);
    }
    
    /**
     * Update the current teacher's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found'], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|nullable|string|max:20',
            'department' => 'sometimes|nullable|string|max:100',
            'specialization' => 'sometimes|nullable|string|max:255',
            'hire_date' => 'sometimes|nullable|date',
            'address' => 'sometimes|nullable|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $validatedData = $validator->validated();
        
        // Update user information
        if (isset($validatedData['name']) || isset($validatedData['email']) || isset($validatedData['phone'])) {
            $userUpdate = [];
            if (isset($validatedData['name'])) $userUpdate['name'] = $validatedData['name'];
            if (isset($validatedData['email'])) $userUpdate['email'] = $validatedData['email'];
            if (isset($validatedData['phone'])) $userUpdate['phone'] = $validatedData['phone'];
            
            $teacher->user->update($userUpdate);
        }
        
        // Update teacher information
        $teacherUpdate = [];
        if (isset($validatedData['department'])) $teacherUpdate['department'] = $validatedData['department'];
        if (isset($validatedData['specialization'])) $teacherUpdate['specialization'] = $validatedData['specialization'];
        if (isset($validatedData['hire_date'])) $teacherUpdate['hire_date'] = $validatedData['hire_date'];
        if (isset($validatedData['address'])) $teacherUpdate['address'] = $validatedData['address'];
        
        if (!empty($teacherUpdate)) {
            $teacher->update($teacherUpdate);
        }
        
        return $this->show();
    }
    
    /**
     * Upload profile image
     */
    public function uploadImage(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found'], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'image' => 'required|string', // Base64 string
            'mime_type' => 'required|string|in:image/jpeg,image/png,image/gif,image/webp',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        try {
            // Decode base64 image
            $imageData = $request->input('image');
            $mimeType = $request->input('mime_type');
            
            // Remove data URL prefix if present
            if (strpos($imageData, 'data:') === 0) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            }
            
            // Validate base64
            $decodedImage = base64_decode($imageData, true);
            if ($decodedImage === false) {
                return response()->json(['message' => 'Invalid image data'], 422);
            }
            
            // Check image size (max 5MB)
            if (strlen($decodedImage) > 5 * 1024 * 1024) {
                return response()->json(['message' => 'Image too large. Maximum size is 5MB'], 422);
            }
            
            // Update teacher profile image
            $teacher->update([
                'profile_image_base64' => $imageData,
                'profile_image_mime_type' => $mimeType,
                'profile_image_updated_at' => now(),
            ]);
            
            return response()->json([
                'message' => 'Profile image uploaded successfully',
                'profile_image' => $teacher->fresh()->profile_image,
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to upload image'], 500);
        }
    }
    
    /**
     * Delete profile image
     */
    public function deleteImage()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        if (!$teacher) {
            return response()->json(['message' => 'Teacher profile not found'], 404);
        }
        
        $teacher->update([
            'profile_image_base64' => null,
            'profile_image_mime_type' => null,
            'profile_image_updated_at' => null,
        ]);
        
        return response()->json(['message' => 'Profile image deleted successfully']);
    }
}
