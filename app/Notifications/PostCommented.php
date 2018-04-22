<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Model\Posts;
use App\Model\Comments;
class PostCommented extends Notification
{
    use Queueable;
    protected $commenter;
    protected $posts;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $commenter, Posts $posts, string $comment )
    {
        $this->commenter = $commenter;
        $this->posts = $posts;
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }
    public function toDatabase($notifiable)
    {
        return [
            'commenter_id' => $this->commenter->id,
            'commenter_username' => $this->commenter->username,
            'post'=> $this->posts->id,
            'comment' => $this->comment,
            'response' => $this->commenter->username." commented on your post: ". $this->comment, 
        ];
    }
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
