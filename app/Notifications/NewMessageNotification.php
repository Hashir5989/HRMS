<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(public Message $message) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $sender = $this->message->user->name ?? 'Someone';
        return (new MailMessage)
            ->subject("New message from {$sender} – HRMS")
            ->greeting("Hello {$notifiable->name}!")
            ->line("{$sender} sent you a message:")
            ->line("\"{$this->message->body}\"")
            ->action('View Chat', url('/chat/' . $this->message->conversation_id))
            ->line('Login to HRMS to reply.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'message',
            'message' => $this->message->body,
            'sender' => $this->message->user->name ?? 'Unknown',
            'sender_id' => $this->message->user_id,
            'conversation_id' => $this->message->conversation_id,
            'link' => '/chat/' . $this->message->conversation_id,
        ];
    }
}
