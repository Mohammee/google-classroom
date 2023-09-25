<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    //name of input not need to show in error messages
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected $dontReport = [
//      UserAlreadyJoinClassroomException::class,
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        //reporting(log exception, send notify to admin) , rendering(show page, redirect)

//        $this->reportable(function (UserAlreadyJoinClassroomException $e) {
//            // array of trace for your error
////            $e->getTrace();
////            $e->getTraceAsString()
//            Log::info($e->getMessage(), [
//                'user_id' => Auth::id(),
//                'classroom_id' => $e->getClassroomId(),
//            ]);
////            return false;
//        })->stop();


        $this->renderable(function (ValidationException $e, Request $request) {
//          return 'fsdf';
            if ($request->expectsJson()) {
                $errors = [];
                foreach ($e->errors() as $input => $err) {
                    $errors[$input] = collect($err)->first();
                }

                return response()->json($errors, 422);
            }

            return redirect()->back()->withInput()->withErrors($e->validator);
        });


        //you can user render inside your custom exception
//        $this->renderable(function (UserAlreadyJoinClassroomException $e, Request $request) {
//            if ($request->expectsJson()) {
//                return response()->json([
//                    'message' => $e->getMessage(),
//                ], 400);
//            }
//
//            return redirect()->route('classrooms.show', $e->getClassroomId());
//        });
    }



}
