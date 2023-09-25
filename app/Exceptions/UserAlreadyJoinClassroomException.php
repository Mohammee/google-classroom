<?php

namespace App\Exceptions;

use App\Mail\CriticalMailable;
use App\Notifications\CriticalErrorNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class UserAlreadyJoinClassroomException extends Exception
{
    protected $classroomId;

    public function setClassroomId($id)
    {
        $this->classroomId = $id;
    }


    public function getClassroomId()
    {
        return $this->classroomId;
    }

    public function render(Request $request)
    {
        if($request->expectsJson()){
            return response()->json([
                'message' => $this->getMessage(),
            ], 400);
        }

        return redirect()->route('classrooms.show', $this->getClassroomId());
    }

    public function report()
    {
        //send notify to external email like moderator
        Notification::route('mail', config('app.errors_email'))
//            ->route('vonage', config('app.errors_phone'))
            ->notify(new CriticalErrorNotification($this));

        Mail::to(config('app.errors_email'))->send(new CriticalMailable($this));
        return false;
    }
}
