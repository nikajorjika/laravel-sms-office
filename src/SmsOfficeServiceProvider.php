<?php

namespace Nikajorjika\SmsOffice;

use App\Channels\SmsOfficeChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Nikajorjika\SmsOffice\SmsOffice;

class SmsOfficeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('sms-office', function ($app) {
            return new SmsOffice();
        });
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'smsoffice');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        Notification::extend('sms-office', function ($app) {
            return new SmsOfficeChannel();
        });
    }
}
