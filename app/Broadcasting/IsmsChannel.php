<?php

namespace App\Broadcasting;

use App\Exceptions\CouldNotSendNotification;
use App\Models\Sms;
use App\Services\GetSmsApi;
use App\Services\IsmsApi;
use App\Services\SmsMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class IsmsChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct(protected IsmsApi $isms, protected GetSmsApi $getSmsApi){}

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @throws CouldNotSendNotification
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
        return false;
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

    protected function sendMessage($recipient, SmsMessage $message)
    {
        if (\mb_strlen($message->content) > 1000) {
            throw CouldNotSendNotification::contentLengthLimitExceeded();
        }
        $kcell = [
            '7701',
            '7702',
            '7778',
            '7775'
        ];
        if(in_array(Str::substr($recipient, 0, 4), $kcell)) {
            return $this->getSmsApi->send($recipient, $message->content);
        }
        return $this->isms->send($recipient, $message->content);
    }
}
