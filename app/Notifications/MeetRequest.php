<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Model\Doggie;
class MeetRequest extends Notification
{
    use Queueable;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $requester;
    protected $requester_dog;
    protected $requested;
    protected $requested_dog;
    public function __construct(User $requester,Doggie $requester_dog,User $requested, Doggie $requested_dog)
    {
        $this->requester = $requester;
        $this->requester_dog = $requester_dog;
        $this->requested = $requested;
        $this->requested_dog = $requested_dog;
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
            'requester_username' => $this->requester->username,
            'requester_dog_name' => $this->requester_dog->name,
            'requested_username' => $this->requested->username,
            'requested_dog_name' => $this->requested_dog->name,
            'meet_response' => $this->requester->username. " 's ". $this->requester_dog->name. " requested to meet your dog: ".$this->requested_dog->name;  
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
