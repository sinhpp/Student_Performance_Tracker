<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'class',
        'dob',
        'gender',
        'status',
        'address',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'guardian_relation',
        'profile_image_path',
        'profile_image_base64',
        'profile_image_mime_type',
        'profile_image_updated_at'
    ];

    protected $casts = [
        'dob' => 'date',
        'profile_image_updated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Get the profile image URL or base64 data
     */
    public function getProfileImageAttribute()
    {
        if ($this->profile_image_base64) {
            return 'data:' . ($this->profile_image_mime_type ?? 'image/jpeg') . ';base64,' . $this->profile_image_base64;
        }
        
        if ($this->profile_image_path) {
            return asset('storage/' . $this->profile_image_path);
        }
        
        return null;
    }

    /**
     * Check if student has a profile image
     */
    public function hasProfileImage(): bool
    {
        return !empty($this->profile_image_base64) || !empty($this->profile_image_path);
    }
}
