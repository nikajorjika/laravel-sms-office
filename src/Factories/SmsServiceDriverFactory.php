<?php

namespace Nikajorjika\SmsOffice\Factories;

use Exception;
use Nikajorjika\SmsOffice\Drivers\GoSmsDriver;
use Nikajorjika\SmsOffice\Drivers\SmsOfficeDriver;

class SmsServiceDriverFactory
{
    public static function createDriver()
    {
        $driver = config('smsoffice.driver');

        switch ($driver) {
            case 'sms-office':
                return new SmsOfficeDriver();
            case 'go-sms':
                return new GoSmsDriver();
            default:
                throw new Exception('Driver not supported');
        }
    }
}
