<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Models\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateCourseRequest;
use OpenApi\Attributes as OA;

class CourseController extends Controller
{
    #[OA\Get(
        path: "/api/courses",
        summary: "Get list of courses",
        tags: ["Courses"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "limit",
                in: "query",
                description: "Number of records per page",
                required: false,
                schema: new OA\Schema(
                    type: "integer",
                    default: 10
                )
            ),
            new OA\Parameter(
                name: "search",
                in: "query",
                description: "Search by course name or course code",
                required: false,
                schema: new OA\Schema(
                    type: "string"
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Courses fetched successfully"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )] 

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

    #[OA\Post(
        path: "/api/courses",
        summary: "Create a new course",
        tags: ["Courses"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["course_name", "description"],
                properties: [
                    new OA\Property(
                        property: "course_name",
                        type: "string",
                        nullable: true
                    ),
                    new OA\Property(
                        property: "description",
                        type: "string",
                        nullable: true
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Course created successfully"
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

    #[OA\Get(
        path: "/api/courses/{id}",
        summary: "Get course by ID",
        tags: ["Courses"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Course ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Course fetched successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Course not found"
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

    #[OA\Put(
        path: "/api/courses/{id}",
        summary: "Update course",
        tags: ["Courses"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Course ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: "course_name",
                        type: "string",
                        nullable: true
                    ),
                    new OA\Property(
                        property: "description",
                        type: "string",
                        nullable: true
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Course updated successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Course not found"
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

    #[OA\Delete(
        path: "/api/courses/{id}",
        summary: "Delete course",
        tags: ["Courses"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Course ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Course deleted successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Course not found"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]

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
