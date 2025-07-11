<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'department',
        'specialization',
        'hire_date',
        'phone',
        'address',
        'status',
        'profile_image_base64',
        'profile_image_mime_type',
        'profile_image_updated_at'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'profile_image_updated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Many-to-many relationship with subjects
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects');
    }

    // Many-to-many relationship with classes
    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'teacher_classes');
    }

    /**
     * Get the profile image URL or base64 data
     */
    public function getProfileImageAttribute()
    {
        if ($this->profile_image_base64) {
            return 'data:' . ($this->profile_image_mime_type ?? 'image/jpeg') . ';base64,' . $this->profile_image_base64;
        }
        
        return null;
    }

    /**
     * Check if teacher has a profile image
     */
    public function hasProfileImage(): bool
    {
        return !empty($this->profile_image_base64);
    }
}
