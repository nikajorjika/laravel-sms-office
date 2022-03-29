<?php

return [
    /**
     * Endpoint for sms office url
     */
    'api_url' => env('SMS_OFFICE_URL', 'http://smsoffice.ge/api/v2/send/'),

    /**
     * Private Key provided by sms office service 
     */
    'key' => env('SMS_OFFICE_KEY', null),

    /**
     * Driver that serves as a channel driver for laravel
     */
    'driver' => env('SMS_OFFICE_DRIVER', 'sms-office'),

    /**
     * Driver that serves as a channel driver for laravel
     */
    'from' => env('SMS_OFFICE_FROM', NULL),

    /**
     * List of drivers that sms office package supports
     */
    'supported_drivers' => ['sms-office', 'log'],
];
