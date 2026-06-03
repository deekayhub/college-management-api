<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_name',
        'course_code',
        'description',
    ];


    public static function generateCourseCode(): string
    {
        $lastCourse = self::latest()->first();

        $nextNumber = $lastCourse
            ? ((int) substr($lastCourse->course_code, 2)) + 1
            : 1;

        return 'CS' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
