<?php

namespace Nikajorjika\SmsOffice;

use Illuminate\Notifications\Notification;
use Nikajorjika\SmsOffice\Facades\SmsOffice;

class SmsOfficeChannel
{
    /**
     * Send function implementation for custom channel
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);
        $phoneNumber = $notifiable->routeNotificationFor('Sms');

        return SmsOffice::message($message)->to($phoneNumber)->send();
    }
}
