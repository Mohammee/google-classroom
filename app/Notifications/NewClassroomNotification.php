<?php

namespace App\Notifications;

use App\Models\Classwork;
use App\Notifications\Channels\HadaraSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;

class NewClassroomNotification extends Notification
//    implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Classwork $classwork)
    {
        //
//        $this->onQueue('notification');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // channels: mail, database, broadcast(pusher, api), vanage(sms) => old(nexmo), slack
//        $via = ['database'];
//        if($notifiable->recieve_mail_notifications){
//            $via[] = 'mail';
//        }
//
//        if($notifiable->recieve_push_notifications){
//            $via[] = 'broadcast';
//        }
//
//        return $via;
        return ['database', FcmChannel::class];
        return ['database', 'mail', 'broadcast', HadaraSmsChannel::class];
//            'vonage'];
    }


    // in gmail only used inline style because if use css files make conflict in your style and gmail style

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $content = __(':name posted a new :type :title', [
            'name' => $this->classwork->user->name,
            'type' => __($this->classwork->type->value),
            'title' => $this->classwork->title
        ]);

        //without subject by default send class name
        return (new MailMessage)
            ->subject(
                __('New :type', [
                    'type' => $this->classwork->type->value
                ])
            )
            ->greeting(
                __('Hi :name', [
                    'name' => $notifiable->name
                ])
            )
            ->line($content)
            ->action(
                __('Go To Classwork'),
                route('classrooms.classworks.show', [$this->classwork->classroom_id, $this->classwork->id])
            )
            ->line('Thank you for using our application!');
//        ->view('', []);
    }


    public function toDatabase(object $notifiable): DatabaseMessage
    {
//        return new DatabaseMessage([
//            //main structure
//            'title' => '',
//            'body' => '',
//            'image' => '',
//            'link' => '',
//            //custom structure
//            'classwork_id' => $this->classwork->id,
//        ]);

        return new DatabaseMessage(array_merge($this->createMessage(), ['classwork_id' => $this->classwork->id]));
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage(array_merge($this->createMessage(), ['classwork_id' => $this->classwork->id]));
    }

    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage())->content(
            __(':name, you have obtained an American passport', [
                'name' => $notifiable->name
            ])
        );
    }

    public function toHadara(object $notifiable)
    {
        return 'Hi man ' . $notifiable->name;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    //used fo both database and broadcast
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }


    protected function createMessage()
    {
        return [
            'content' => __(':name posted a new :type :title', [
                'name' => $this->classwork->user->name,
                'type' => __($this->classwork->type->value),
                'title' => $this->classwork->title
            ]),
            'title' => __('New :type', [
                'type' => $this->classwork->type->value
            ]),
            'link' => route('classrooms.classworks.show', [$this->classwork->classroom_id, $this->classwork->id]),
            'image' => '',

        ];
    }

    public function toFcm($notifiable)
    {
        $content = __(':name posted a new :type :title', [
            'name' => $this->classwork->user->name,
            'type' => __($this->classwork->type->value),
            'title' => $this->classwork->title
        ]);
        return FcmMessage::create()
            ->setData([
                'classwork_id' => $this->classwork->id,
                'user_id' => $this->classwork->user_id,
            ])
            ->setNotification(
                \NotificationChannels\Fcm\Resources\Notification::create()
                    ->setTitle('Account Activated')
                    ->setBody($content)
                    ->setImage('http://example.com/url-to-image-here.png')
            )
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('analytics'))
                    ->setNotification(AndroidNotification::create()->setColor('#0A0A0A'))
            )->setApns(
                ApnsConfig::create()
                    ->setFcmOptions(ApnsFcmOptions::create()->setAnalyticsLabel('analytics_ios'))
            );
    }
}
