<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlreadyJoinClassroomException;
use App\Models\Classroom;
use App\Models\Scopes\UserClassroomScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class JoinClassroomController extends Controller
{
    public function create($id)
    {


        $classroom = Classroom::withoutGlobalScope(UserClassroomScope::class)->status('active')->findOrFail($id);

//        try {
            $this->exists($classroom, Auth::id());
//        }catch (\Exception){
//            return Redirect::route('classrooms.show', $id)->with('success', 'You are joined in this classroom.');
//        }

        return view('classrooms.join', compact('classroom'));
    }

    public function store(Request $request, $id)
    {
        $request->validate(['role' => 'in:student,teacher']);

        $classroom = Classroom::withoutGlobalScope(UserClassroomScope::class)->status('active')->findOrFail($id);

        // use laravel exception
//        try {
            $this->exists($classroom, Auth::id());
//        }catch (\Exception){
//            return Redirect::route('classrooms.show', $id);
//        }

        DB::table('classroom_user')->insert([
            'classroom_id' => $id,
            'user_id' => Auth::id(),
            'created_at' => now(),
            'role' => $request->input('role', 'student')
        ]);

        return Redirect::route('classrooms.show', $id);
    }

    private function exists(Classroom $classroom, $user_id)
    {
//        $exists = DB::table('classroom_user')
//            ->where([
//                'classroom_id' => $classroom_id,
//                'user_id' => Auth()->id()
//            ])->exists();

        $exists = $classroom->users()->where('id', '=', $user_id)->exists();
//        $exists = $classroom->users()->wherePivot('user_id', '=', $user_id)->exists();

        if($exists){
            $ex = new UserAlreadyJoinClassroomException('You are already joined.');
            $ex->setClassroomId($classroom->id);

            throw $ex;
        }
    }
}
