<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class ClassworkCommentTestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Comment $comment)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New Comment in Classwork with :title', ['title' => $this->comment->commentable->title]))
            ->greeting('Hi ' . $notifiable->name)
            ->line(Str::words($this->comment->content, 20))
            ->action(
                'Go To',
                route(
                    'classrooms.classworks.show',
                    [$this->comment->commentable->classroom_id, $this->comment->commentable->id]
                ))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => __(':name comment in classwork :title', [
                'name' => $this->comment->user->name,
                'title' => $this->comment->commentable->title
            ]),
            'content' => Str::words($this->comment->content, 10),
            'link' => route(
                'classrooms.classworks.show',
                [$this->comment->commentable->classroom_id, $this->comment->commentable->id]
            ),
        ];
    }

}
