<?php

namespace Nikajorjika\SmsOffice\Drivers;

use Illuminate\Support\Facades\Http;
use Nikajorjika\SmsOffice\Contracts\SmsServiceContract;

class GoSmsDriver implements SmsServiceContract
{
    const QUERY_STRING = 'api_key=%s&to=%s&from=%s&text=%s&urgent=%s';

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
        $url = 'https://api.gosms.ge/api/sendsms';
        $key = config('smsoffice.key');
        $from = config('smsoffice.from');

        return $url.'?'.sprintf(self::QUERY_STRING, $key, $to, $from, $message, $urgent);
    }

}
