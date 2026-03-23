<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GlobalNotification extends Notification
{
    use Queueable;

    private $data;

    public function __construct($title, $message, $type = 'info')
    {
        $this->data = [
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $this->getIcon($type),
        ];
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return $this->data;
    }

    private function getIcon($type)
    {
        return match($type) {
            'alert' => 'bi-exclamation-triangle',
            'success' => 'bi-check-circle',
            'billing' => 'bi-credit-card',
            default => 'bi-info-circle',
        };
    }
}
