<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments');
    }
}
