<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $fillable = [
        'name',
        'grade_level',
        'description',
        'status'
    ];

    // Many-to-many relationship with teachers (if needed)
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_classes');
    }

    // One-to-many relationship with students
    public function students()
    {
        return $this->hasMany(Student::class, 'class', 'name');
    }
}
