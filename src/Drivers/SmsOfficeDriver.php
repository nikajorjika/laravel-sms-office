<?php

namespace Nikajorjika\SmsOffice\Drivers;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Nikajorjika\SmsOffice\Contracts\SmsServiceContract;

class SmsOfficeDriver implements SmsServiceContract
{
    const QUERY_STRING = 'key=%s&destination=%s&sender=%s&content=%s&urgent=%s';

    public function send(string $to, string $message, bool $urgent = false): string
    {
        $fullServiceUrl = $this->getServiceUrl($to, $message, $urgent);

        return Http::get($fullServiceUrl)->body();
    }

    /**
     * Get Sevice final url string
     *
     * @param  string  $destination
     * @param  string  $message
     *
     * @return string
     */
    private function getServiceUrl(string $to, string $message, bool $urgent = false): string
    {
        $url = 'http://smsoffice.ge/api/v2/send/';
        $key = config('smsoffice.key');
        $from = config('smsoffice.from');

        return $url . '?' . sprintf(self::QUERY_STRING, $key, $to, $from, $message, $urgent ? 'true' : 'false');
    }
}
