<?php

namespace Nikajorjika\SmsOffice\Facades;

use Illuminate\Support\Facades\Facade;

class SmsOffice extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sms-office';
    }
}
