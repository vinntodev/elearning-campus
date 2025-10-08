<?php

namespace App\Notifications;

use App\Models\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewAssignmentNotification extends Notification
{
    use Queueable;

    protected $assignment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tugas Baru: ' . $this->assignment->title)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Ada tugas baru di mata kuliah ' . $this->assignment->course->name)
            ->line('Judul: ' . $this->assignment->title)
            ->line('Deskripsi: ' . $this->assignment->description)
            ->line('Deadline: ' . $this->assignment->deadline->format('d M Y H:i'))
            ->line('Segera kerjakan tugas ini!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
