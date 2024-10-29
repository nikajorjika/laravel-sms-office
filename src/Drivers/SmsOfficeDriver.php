<?php

namespace Nikajorjika\SmsOffice\Drivers;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Nikajorjika\SmsOffice\Contracts\SmsServiceContract;

class SmsOfficeDriver implements SmsServiceContract
{
    const QUERY_STRING = 'key=%s&destination=%s&sender=%s&content=%s';

    public function send(string $to, string $message): string
    {
        $fullServiceUrl = $this->getServiceUrl($to, $message);

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
    private function getServiceUrl(string $to, string $message): string
    {
        $url = 'http://smsoffice.ge/api/v2/send/';

        return $url.'?'.$this->getReplacedQueryString($to, $message);
    }

    /**
     * Get query string with replaced variables according to SMS Office documentation
     *
     * @param  string  $destination
     * @param  string  $message
     *
     * @return string
     */
    private function getReplacedQueryString(string $destination, string $message): string
    {
        $key = config('smsoffice.key');
        $from = config('smsoffice.from');

        return sprintf(self::QUERY_STRING, $key, $destination, $from, $message);
    }
}
