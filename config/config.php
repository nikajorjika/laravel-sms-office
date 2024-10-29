<?php

return [
    /**
     * Private Key provided by sms office service
     */
    'key' => env('SMS_OFFICE_KEY', null),

    /**
     * Driver that serves as a channel driver for laravel
     */
    'driver' => env('SMS_OFFICE_DRIVER', 'sms-office'),

    /**
     * This key defines sender name
     * for the sms to be delivered from
     */
    'from' => env('SMS_OFFICE_FROM', NULL),

    /**
     * List of drivers that sms office package supports
     */
    'supported_drivers' => ['sms-office', 'go-sms', 'log'],

    /**
     * Define no sms code for the user to unsubscribe
     */
    'no_sms_code' => env('SMS_OFFICE_NOSMS', NULL),

];
