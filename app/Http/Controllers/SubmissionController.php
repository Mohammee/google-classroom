<?php

namespace App\Http\Controllers;

use App\Models\Classwork;
use App\Models\ClassworkUser;
use App\Models\Submission;
use App\Rules\ForbiddenFile;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{


    public function store(Request $request, Classwork $classwork)
    {
//        Gate::authorize('submissions.create', [$classwork]);
        $request->validate([
            'files' => 'required|array',
            'files.*' => ['file', new ForbiddenFile('text/x-php', "application/x-dosexec")]
        ]);

        $assigned = $classwork->users()->where('id', Auth::id())->exists();

        abort_if(!$assigned, 403);

        DB::beginTransaction();

        $data = [];
        try {
            foreach ($request->file('files') as $file) {
                $data [] = [
//                    'user_id' => Auth::id(),
                    'classwork_id' => $classwork->id,
                    'content' => $file->store("submissions/{$classwork->id}"),
                    'type' => 'file',
                    //use with insert
//                   'created_at' => now(),
//                   'updated_at' => now(),
                ];
            }

//            Submission::insert($data);
            $user = Auth::user();
            $user->submissions()->createMany($data);

            ClassworkUser::where([
                'user_id' => $user->id,
                'classwork_id' => $classwork->id,
            ])->update([
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Work Submitted.');
    }

    public function file(Submission $submission)
    {
        // this route know as file proxy => for hidden file (name & path) , route proxy is between user and original file
        // you can use this route for making counter in this file like(show, download) and so on

//        abort_if(Auth::user()->id != $submission->user_id || !Storage::exists($submission->content), 404);

        /*
         select * from classroom_user
          where user_id = ?
        and role = teacher
          and exists (select 1 from classworks where classworks.classroom_id = classroom_user.classroom_id
        and  exists (
          select 1 from submissions where submissions.classwork_id = classworks.id  and submissions.id = ?)
          )
         */
        $user = Auth::user();

//        dd(DB::select(
//            'select * from classroom_user
//          where user_id = ?
//        and role = ?
//          and exists (select 1 from classworks where classworks.classroom_id = classroom_user.classroom_id
//        and  exists (
//          select 1 from submissions where submissions.classwork_id = classworks.id  and submissions.id = ?)
//          )',
//            [$user->id, 'teacher', $submission->id]
//        ));

//            dd(DB::table('classroom_user')->where([
//                'user_id' => $user->id,
//                'role' => 'teacher'
//            ])->whereExists(function (Builder $q) use ($submission) {
//                $q->select(DB::raw('1'))
//                    ->from('classworks')
//                    ->whereColumn([
//                        'classworks.classroom_id' => 'classroom_user.classroom_id'
//                    ])->whereExists(function (Builder $q) use ($submission) {
//                        $q->select(DB::raw('1'))
//                            ->from('submissions')
//                            ->whereColumn([
//                                'submissions.classwork_id' => 'classworks.id',
//                            ])->where([
//                                'submissions.id' => $submission->id,
//                            ]);
//                    });
//            })->get());

        $isTeacher = $submission->classwork->classroom->teachers()->where('id', $user->id)->exists();
        $isOwner = $submission->user_id = $user->id;

        if (!$isTeacher && !$isOwner) {
            abort(403);
        }
//        $path = Storage::path($submission->content);
//        return Storage::download($submission->content);
        return Response::file(storage_path('app/' . $submission->content));
    }
}
