<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $enrollments = Enrollment::with(['student', 'course'])->get();
            return response()->json([
                'success' =>true,
                'message' => 'enrollments fetched successfully',
                'data' => $enrollments,
            ]);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch enrollments',
            ], 500);
        }
    } 

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnrollmentRequest $request)
    {
        try{
            $validated = $request->validated();
            $student = Student::findOrFail($validated['student_id']);

            $enrolled = $student->courses()
                ->where('courses.id', $validated['course_id'])
                ->exists();

            if ($enrolled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student already enrolled in this course',
                ], 422);
            }

            $student->courses()->attach($validated['course_id']);

            return response()->json([
                'success' => true,
                'message' => 'Enrollment created successfully',
            ], 201);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to create enrollment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->delete();
            return response()->json([
                'success' => true,
                'message' => 'Enrollment deleted successfully',
            ]);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete enrollment',
            ], 500);
        }
    }
}
