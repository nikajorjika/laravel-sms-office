<?php

namespace Nikajorjika\SmsOffice;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Nikajorjika\SmsOffice\Contracts\SmsOffice as ContractsSmsOffice;
use Nikajorjika\SmsOffice\SmsOfficeChannel;
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
        $this->app->bind(ContractsSmsOffice::class, SmsOffice::class);
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

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('smsoffice.php'),
            ], 'config');
        }
    }
}
