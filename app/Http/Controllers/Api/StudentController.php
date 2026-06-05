<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
// use OpenApi\Attributes\Response;

class StudentController extends Controller
{
    #[OA\Get(
        path: "/api/students",
        summary: "Get a list of students",
        tags: ["Students"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "limit",
                in: "query",
                description: "Number of records per page",
                required: false,
                schema: new OA\Schema(type: "integer", default: 10)
            ),
            new OA\Parameter(
                name: "search",
                in: "query",
                description: "Search by name, phone, or email",
                required: false,
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response( 
                response: 200,
                description: "Students fetched successfully",
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]

    public function index(Request $request)
    {        
        $limit = $request->get('limit', 10);
        $students = Student::query();
        
        if($request->filled('search')){
            $search = $request->search;
            $students->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $students = $students->paginate($limit);
        return response()->json([
          'success' => true,
          'message' => 'Students fetched successfully',
          'data' => $students->items(),
          'pagination' => [
            'current_page' => $students->currentPage(),
            'last_page' => $students->lastPage(),
            'per_page' => $students->perPage(),
            'total' => $students->total(),
          ],
        ], 200);

    }

    #[OA\Post(
        path: "/api/students",
        summary: "Create a new student",
        tags: ["Students"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["first_name", "last_name", "email", "phone"],
                properties: [
                    new OA\Property(
                        property: "first_name",
                        type: "string"
                    ),
                    new OA\Property(
                        property: "last_name",
                        type: "string"
                    ),
                    new OA\Property(
                        property: "email",
                        type: "string",
                        format: "email"
                    ),
                    new OA\Property(
                        property: "phone",
                        type: "string"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Student created successfully"
            )
        ]
    )]
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

    #[OA\Get(
        path: "/api/students/{id}",
        summary: "Get Student By ID",
        tags: ["Students"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Student ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Student fetched successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Student not found"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]

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

    #[OA\Put(
        path: "/api/students/{id}",
        summary: "Update Student",
        tags: ["Students"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Student ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["first_name", "last_name", "email", "phone"],
                properties: [
                    new OA\Property(
                        property: "first_name",
                        type: "string",
                        nullable: true
                    ),
                    new OA\Property(
                        property: "last_name",
                        type: "string",
                        nullable: true
                    ),
                    new OA\Property(
                        property: "email",
                        type: "string",
                        format: "email",
                        nullable: true
                    ),
                    new OA\Property(
                        property: "phone",
                        type: "string",
                        nullable: true
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Student updated successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Student not found"
            ),
            new OA\Response(
                response: 422,
                description: "Validation failed"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]

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

    #[OA\Delete(
        path: "/api/students/{id}",
        summary: "Delete Student",
        tags: ["Students"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Student ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Student deleted successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Student not found"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]

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
