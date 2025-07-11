<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'teacher_name'
    ];

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
