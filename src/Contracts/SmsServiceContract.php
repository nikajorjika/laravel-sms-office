<?php

namespace Nikajorjika\SmsOffice\Contracts;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

interface SmsServiceContract
{
    public function send(string $to, string $message): string;
}
