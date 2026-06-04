<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Models\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateCourseRequest;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cources = Course::query();
        $limit = $request->get('limit', 10);

        if($request->filled('search')){
            $search = $request->search;
            $cources->where('course_name', 'like', "%{$search}%")
                ->orWhere('course_code', 'like', "%{$search}%");
        }
        $cources = $cources->paginate($limit);

        return response()->json([
            'success' => true,
            'message' => 'cources fetched successfully',
            'data' => $cources,  
            'patinations' => [
                'current_page' => $cources->currentPage(),
                'last_page' => $cources->lastPage(),
                'per_page' => $cources->perPage(),
                'total' => $cources->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $course = Course::create([
                'course_name' => $validatedData['course_name'],
                'course_code' => Course::generateCourseCode(),
                'description' => $validatedData['description'],
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'course created successfully',
                'data' => $course,
            ], 201);

        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Course',
            ], 500);        
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $course = Course::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Course fetched successfully',
                'data' => $course,
            ], 200);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
            ], 404);

        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, string $id)
    {
        try{
            $course = Course::findOrFail($id);
            $course->update([
                'course_name' => $request->input('course_name', $course->course_name),
                'description' => $request->input('description', $course->description),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully',
                'data' => $course,

            ], 200);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
            ], 404);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update course',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::find($id);
        if(!$course){
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
            ], 404);
        }else{
            $course->delete();
            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully',    
            ], 200);
        }
    }
}
