<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewHulaChatMessageNotification extends Notification
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Hula Hula Zóna')
            ->icon("'" . asset('images/app/hula_hula_sq.png') . "'")
            ->body(__('notifications.push.new_hula_chat_message', [], $notifiable->locale))
            ->action('View App', 'notification_action')
            ->vibrate([100, 20, 50, 30, 100, 20, 50, 50, 200, 50, 50]);
    }
}
