<?php
namespace App\Observers;

use App\Models\Notification;
use App\Models\User;
use App\Notifications\SiteNotificationMail;
use Illuminate\Support\Facades\Mail;

class NotificationObserver
{
    public function created(Notification $notif): void
    {
        $user = User::find($notif->user_id);
        if (!$user || !$user->email) return;

        try {
            $user->notify(new SiteNotificationMail($notif));
        } catch (\Exception $e) {
            \Log::error('Mail notification failed: ' . $e->getMessage());
        }
    }
}
