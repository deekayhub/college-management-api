<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
// use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        return response()->json([
          'success' => true,
          'message' => 'Students fetched successfully',
          'data' => $students,  
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $student = Student::create($validatedData);
            
            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'data' => $student,
            ], 201);

        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to create student',
            ], 500);        
        }

        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try{
            $student = Student::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Student fetched successfully',
                'data' => $student,
            ], 200);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
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
    public function update(UpdateStudentRequest $request, $id)
    {
        try{
            $student = Student::findOrFail($id);
            $student->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully',
                'data' => $student,

            ], 200);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
            ], 404);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);
        if(!$student){
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
            ], 404);
        }else{
            $student->delete();
            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully',    
            ], 200);
        }
    }
}
