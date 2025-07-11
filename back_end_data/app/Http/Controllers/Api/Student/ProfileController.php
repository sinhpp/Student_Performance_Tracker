<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Get the current student's profile
     */
    public function show()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->with('user')->first();
        
        if (!$student) {
            return response()->json(['message' => 'Student profile not found'], 404);
        }
        
        $profile = [
            'id' => $student->id,
            'name' => $student->user->name,
            'email' => $student->user->email,
            'phone' => $student->user->phone,
            'student_id' => 'STU' . str_pad($student->id, 3, '0', STR_PAD_LEFT),
            'class' => $student->class,
            'status' => $student->status,
            'dob' => $student->dob ? $student->dob->format('Y-m-d') : null,
            'gender' => $student->gender,
            'address' => $student->address,
            'guardian_name' => $student->guardian_name,
            'guardian_phone' => $student->guardian_phone,
            'guardian_email' => $student->guardian_email,
            'guardian_relation' => $student->guardian_relation,
            'profile_image' => $student->profile_image,
            'has_profile_image' => $student->hasProfileImage(),
            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at,
        ];
        
        return response()->json($profile);
    }
    
    /**
     * Update the current student's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['message' => 'Student profile not found'], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|nullable|string|max:20',
            'dob' => 'sometimes|nullable|date',
            'gender' => 'sometimes|in:male,female,other',
            'address' => 'sometimes|nullable|string|max:500',
            'guardian_name' => 'sometimes|nullable|string|max:255',
            'guardian_phone' => 'sometimes|nullable|string|max:20',
            'guardian_email' => 'sometimes|nullable|email|max:255',
            'guardian_relation' => 'sometimes|nullable|string|max:100',
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
            
            $student->user->update($userUpdate);
        }
        
        // Update student information
        $studentUpdate = [];
        if (isset($validatedData['dob'])) $studentUpdate['dob'] = $validatedData['dob'];
        if (isset($validatedData['gender'])) $studentUpdate['gender'] = $validatedData['gender'];
        if (isset($validatedData['address'])) $studentUpdate['address'] = $validatedData['address'];
        if (isset($validatedData['guardian_name'])) $studentUpdate['guardian_name'] = $validatedData['guardian_name'];
        if (isset($validatedData['guardian_phone'])) $studentUpdate['guardian_phone'] = $validatedData['guardian_phone'];
        if (isset($validatedData['guardian_email'])) $studentUpdate['guardian_email'] = $validatedData['guardian_email'];
        if (isset($validatedData['guardian_relation'])) $studentUpdate['guardian_relation'] = $validatedData['guardian_relation'];
        
        if (!empty($studentUpdate)) {
            $student->update($studentUpdate);
        }
        
        return $this->show();
    }
    
    /**
     * Upload profile image
     */
    public function uploadImage(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['message' => 'Student profile not found'], 404);
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
            
            // Update student profile image
            $student->update([
                'profile_image_base64' => $imageData,
                'profile_image_mime_type' => $mimeType,
                'profile_image_updated_at' => now(),
            ]);
            
            return response()->json([
                'message' => 'Profile image uploaded successfully',
                'profile_image' => $student->fresh()->profile_image,
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
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['message' => 'Student profile not found'], 404);
        }
        
        $student->update([
            'profile_image_base64' => null,
            'profile_image_path' => null,
            'profile_image_mime_type' => null,
            'profile_image_updated_at' => null,
        ]);
        
        return response()->json(['message' => 'Profile image deleted successfully']);
    }
}
