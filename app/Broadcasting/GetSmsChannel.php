<?php

namespace App\Broadcasting;

use App\Models\Sms;
use App\Services\GetSmsApi;
use App\Services\SmsMessage;
use Illuminate\Notifications\Notification;

class GetSmsChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct(protected GetSmsApi $getSms){}

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return bool|null
     */
    public function send($notifiable, Notification $notification): ?bool
    {
        if (! ($to = $this->getRecipient($notifiable, $notification))) {
            return null;
        }

        $message = $notification->{'toSms'}($notifiable);

        if (\is_string($message)) {
            $message = new SmsMessage($message);
        }
        if($this->sendMessage($to, $message)) {
            Sms::create([
                'phone_number' => $to
            ]);
            return true;
        }
    }

    /**
     * Gets phone from the given notifiable.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return string
     */
    protected function getRecipient($notifiable, Notification $notification): string
    {
        return $notifiable->routeNotificationFor('sms', $notification);
    }

    protected function sendMessage($recipient, SmsMessage $message): bool
    {
        return $this->getSms->send($recipient, $message->content);
    }
}
