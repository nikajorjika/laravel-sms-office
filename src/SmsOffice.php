<?php

namespace Nikajorjika\SmsOffice;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsOffice
{
    const QUERY_STRING = 'key=%s&destination=%s&sender=%s&content=%s';

    protected $driver;
    protected $key;
    protected $url;
    protected $from;
    protected $to;
    protected $message;
    protected $supportedDrivers;
    protected $dryRun = 'no';

    /**
     * Construct new sms office class with it's essential variables
     * @return void
     */
    public function __construct()
    {
        $this->url = config('smsoffice.api_url');
        $this->key = config('smsoffice.key');
        $this->from = config('smsoffice.sender');
        $this->driver = config('smsoffice.driver');
        $this->supportedDrivers = config('smsoffice.supported_drivers');
    }

    /**
     * Get Sevice final url string
     * 
     * @param string $destination
     * @param string $message
     * 
     * @return string
     */
    public function send()
    {
        $this->validateConfigParams();
        $this->validateSmsParams();
        if (strlen($this->to) !== 12) {
            throw new Exception('Destination phone number is not valid!');
        }

        if (strtolower($this->driver) === 'log') {
            return $this->logMessage($this->to, $this->message);
        }

        $fullServiceUrl = $this->getServiceUrl($this->to, $this->message);

        return Http::get($fullServiceUrl)->body();
    }

    /**
     * Get Sevice final url string
     * 
     * @param string $destination
     * @param string $message
     * 
     * @return string
     */
    public function getServiceUrl(string $destination, string $message): string
    {
        return $this->url . '?' . $this->getReplacedQueryString($destination, $message);
    }

    /**
     * Get query string with replaced variables according to SMS Office documentation
     * @param string $destination
     * @param string $message 
     * 
     * @return string
     */
    public function getReplacedQueryString(string $destination, string $message): string
    {
        return sprintf(self::QUERY_STRING, $this->key, $destination, $this->from, $message);
    }


    /**
     * Generates log for faking sms functionality without sending actual sms
     * 
     * @return string
     */
    public function logMessage(string $destination, string $message): string
    {
        $logMessage = 'From: ' . $this->from . ' To: ' . $destination . '/n MESSAGE:/n' . $message;

        Log::info($logMessage);

        return $logMessage;
    }

    /**
     * Validate phone number based on Georgian national format
     * function returns corrected phone number if correction is successful
     * @param string $phone
     * 
     * @return string
     */
    public static function getValidatedPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($phone, 0, 3) != '995') {
            $phone = '995' . $phone;
        }

        return $phone;
    }

    /**
     * Check if all the parameters required for the package is actually provided
     * it will throw an error if any parameters are missing from the config file
     * 
     * @param string $phone
     * 
     * @throws Exception
     * @return bool
     */
    public function validateConfigParams(): bool
    {
        if (!$this->from) {
            throw $this->getMissingParamException('SMS_OFFICE_FROM');
        }
        if (!$this->key) {
            throw $this->getMissingParamException('SMS_OFFICE_KEY');
        }
        if (!$this->driver) {
            throw $this->getMissingParamException('SMS_OFFICE_DRIVER');
        }
        if (!in_array($this->driver, $this->supportedDrivers)) {
            throw new Exception("SMS_OFFICE_DRIVER should be one of these values: " . implode(', ', $this->supportedDrivers));
        }

        return true;
    }

    /**
     * Check if all the nessesary sms office params are set
     * 
     * @throws Exception
     * @return bool
     */
    public function validateSmsParams(): bool
    {
        if (!$this->to || !$this->message) {
            throw new Exception("Please provide 'to()' and 'message()' methods, while building the notification message!");
        }
        return true;
    }

    /**
     * Get parameter is missing exception with generic text
     * @param string $parameterName
     * 
     * @return Exception
     */
    public function getMissingParamException(string $parameterName): Exception
    {
        return new Exception("$parameterName is not provided.");
    }

    /**
     * Set 'dryRun' property to given value
     * @param string $dry
     * 
     * @return self
     */
    public function dryrun(string $dry = 'yes'): self
    {
        $this->dryrun = $dry;

        return $this;
    }


    /**
     * Set 'from' property to given value
     * @param string $dry
     * 
     * @return self
     */
    public function from($from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set 'to' property to given value
     * @param string $to
     * 
     * @return self
     */
    public function to(string $to): self
    {
        $this->to = self::getValidatedPhoneNumber($to);

        return $this;
    }

    /**
     * Set 'message' property to given value
     * @param string $dry
     * 
     * @return self
     */
    public function message($message): self
    {
        $this->message = strlen($message) !== strlen(utf8_decode($message)) ? rawurlencode($message) : $message;

        return $this;
    }
}
