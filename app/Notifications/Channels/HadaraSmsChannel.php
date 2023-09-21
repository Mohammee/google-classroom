<?php

namespace App\Notifications\Channels;

use App\Services\HadaraSms;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class HadaraSmsChannel
{

    //must define method send
    public function send(object $notifiable, Notification $notification)
    {
     $service = new HadaraSms(config('services.hadara.key'));

      $service->send($notifiable->routeNotificationForHadara($notification),
          $notification->toHadara($notifiable));
    }
}
