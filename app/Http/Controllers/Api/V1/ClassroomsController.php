<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassroomCollection;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class ClassroomsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // when used one application not need abiliites by if need to work like facebook, google use password library more
        // strong in this case from sanctum
        if(!Auth::guard('sanctum')->user()->tokenCan('classrooms.read')){
            abort(403);
        }
//        return Classroom::all();

        $classrooms = Classroom::with('user:id,name', 'topics')
            ->withCount('students as students')->paginate();



//you must add column used for
//        return $classrooms;

        //you can use responses for more data like status code && headers
//        return response()->json($classrooms, 200,[
//            'x-test' => 'test'
//        ]);

//        return ClassroomResource::collection($classrooms);
        return new ClassroomCollection($classrooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*
         * use api resource for needed only not always(*hint)
         * form-data in post man its used like form-multipart in html form used for send file
         * url-form send data like query string
         */
//        try {
//            //laravel by default make exception and redirect to page (home)
//            // to avoid this send Accept header to application/json
//            $request->validate([
//                'name' => ['required']
//            ]);
//        }catch(\Throwable){
//            return Response::json(['Name is required.'], 400);
//        }

        $request->validate([
                'name' => ['required']
            ]);

        $classroom = Classroom::create($request->all());

        // laravel by default return 201 if response model for all post request(for custom use json)
        return response()->json([
            'code' => 100,
            'message' => __('Classroom Created'),
             'classroom' => $classroom
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
//        return $classroom->load('user')->loadCount('students');
        return new ClassroomResource($classroom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        // in put send urlencoded if send form-data return validation error
        $request->validate([
           'name' => ['sometimes', 'required', Rule::unique('classrooms')->ignore($classroom->id)],
           'section' => ['sometimes', 'required']
        ]);

        $classroom->update($request->all());

        return [
            'code' => 100,
            'message' => __('Classroom Updated'),
            'classroom' => $classroom
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Classroom::destroy($id);

        return Response::json([], 204);
    }
}
