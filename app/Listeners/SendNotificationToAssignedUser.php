<?php

namespace App\Listeners;

use App\Jobs\SendClassroomNotification;
use App\Notifications\NewClassroomNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNotificationToAssignedUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
//        $user = User::first();
//
//        $user->notify(new NewClassroomNotification($event->classwork));
//
//        foreach($event->classwork->users as $user){
//            $user->notify(new NewClassroomNotification($event->classwork));
//        };

        $job = new SendClassroomNotification(
            $event->classwork->users,
            new NewClassroomNotification($event->classwork));
//
        $job->onQueue('y');
//        using job
        dispatch($job)->onQueue('notification')->onQueue('z');

        //if you have alot of queue in same job take alot of time so the most usefull solution is make alot of queue and then make working
        //for all job with different (نعمل عملية اولويات)
        //php artisan queue:work --queue=x,y,z,default
        //php artisan queue:work --queue=x,y,z,default

        //use supervise program in server  this app if job failed rejob  not as cron job
        //in shared host use cron job with php artisan queue:work --queue=x,y,z,default --once
        //use once because cron job must finish

//        SendClassroomNotification::dispatch($event->classwork->users, new NewClassroomNotification($event->classwork));
//

//            Notification::send($event->classwork->users, new NewClassroomNotification($event->classwork));
    }
}
