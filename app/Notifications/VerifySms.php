<?php

namespace App\Notifications;

use App\Broadcasting\GetSmsChannel;
use App\Broadcasting\IsmsChannel;
use App\Services\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class VerifySms extends Notification
{
    use Queueable;

    /** @var array */
    public array $channels = [IsmsChannel::class];
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $parsedUrl = parse_url(config('app.url'));
        if ($parsedUrl && isset($parsedUrl['host'])) {
            $zone = Str::afterLast($parsedUrl['host'], '.');
            if($zone == 'uz') {
                $this->channels = [GetSmsChannel::class];
            }
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $this->channels;
    }

    /**
     * Get the sms representation of the notification.
     */
    public function toSms(object $notifiable)
    {
        return SmsMessage::create($notifiable->getCode());
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
