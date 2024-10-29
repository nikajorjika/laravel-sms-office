<?php

namespace Nikajorjika\SmsOffice\Contracts;

interface SmsServiceContract
{
    public function send(string $to, string $message, bool $urgent): string;
}
