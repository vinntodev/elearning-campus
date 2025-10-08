<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewGradeNotification extends Notification
{
    use Queueable;

    protected $submission;

    /**
     * Create a new notification instance.
     */
    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
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
            ->subject('Tugas Dinilai: ' . $this->submission->assignment->title)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Tugas Anda telah dinilai')
            ->line('Mata Kuliah: ' . $this->submission->assignment->course->name)
            ->line('Judul Tugas: ' . $this->submission->assignment->title)
            ->line('Nilai: ' . $this->submission->score)
            ->line('Terima kasih telah mengumpulkan tugas!');
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
