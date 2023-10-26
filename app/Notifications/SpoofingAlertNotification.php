<?php

namespace App\Notifications;

use App\Mail\SpoofingMail;
use App\Models\Track;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;

class SpoofingAlertNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user, public Track $track)
    {
        //
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

    public function toMail(object $notifiable): Mailable
    {
        return (new SpoofingMail($this->user))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => "User {$this->user->full_name} has detected spoofing",
            'description' => "The user {$this->user->full_name} has detected spoofing at location of {$this->track->latitude}, {$this->track->latitude}",
            'link' => route('team-leader.tracking.show', $this->user->id),
        ];
    }
}
