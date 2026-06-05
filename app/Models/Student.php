<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id');
    }


    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
