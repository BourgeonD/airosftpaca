<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Notification as SiteNotif;

class SiteNotificationMail extends Notification
{
    public function __construct(public SiteNotif $notif) {}

    public function via($notifiable): array { return ['mail']; }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[AirsoftPACA] ' . $this->notif->title)
            ->view('emails.site-notification', [
                'notif' => $this->notif,
                'user'  => $notifiable,
            ]);
    }
}
