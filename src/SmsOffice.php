<?php

namespace Nikajorjika\SmsOffice;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Nikajorjika\SmsOffice\Contracts\SmsOffice as SmsOfficeContract;

class SmsOffice implements SmsOfficeContract
{
    const QUERY_STRING = 'key=%s&destination=%s&sender=%s&content=%s';

    protected $driver;
    protected $key;
    protected $url;
    protected $from;
    protected $to;
    protected $message;
    protected $noSmsCode;
    protected $supportedDrivers;
    protected $dryRun = 'no';

    /**
     * Construct new sms office class with it's essential variables
     * @return void
     */
    public function __construct()
    {
        $this->url = config('smsoffice.api_url');
        $this->key = config('smsoffice.key') ?? '';
        $this->from = config('smsoffice.from') ?? '';
        $this->driver = config('smsoffice.driver') ?? 'log';
        $this->noSmsCode = config('smsoffice.no_sms_code') ?? '';
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
    public function send(): string
    {
        $this->validateConfigParams();
        $this->validateSmsParams();
        if (strlen($this->getTo()) !== 12) {
            throw new Exception('Destination phone number is not valid!');
        }

        if ($this->getDriver() === 'log') {
            return $this->logMessage($this->getTo(), $this->getMessage());
        }

        $fullServiceUrl = $this->getServiceUrl($this->getTo());

        return Http::get($fullServiceUrl)->body();
    }

    /**
     * Get Sevice final url string
     * 
     * @param string $destination
     * 
     * @return string
     */
    public function getServiceUrl(string $destination): string
    {
        return $this->getUrl() . '?' . $this->getReplacedQueryString($destination, $this->getMessage());
    }

    /**
     * Get query string with replaced variables according to SMS Office documentation
     * @param string $destination
     * @param string $message 
     * 
     * @return string
     */
    public function getReplacedQueryString(string $destination): string
    {
        return sprintf(self::QUERY_STRING, $this->key, $destination, $this->getFrom(), $this->getMessage());
    }


    /**
     * Generates log for faking sms functionality without sending actual sms
     * @param string $destination
     * 
     * @return string
     */
    public function logMessage(string $destination): string
    {
        $logMessage = 'From: ' . $this->getFrom() . ' To: ' . $destination . '/n MESSAGE:/n' . $this->getMessage();

        Log::info($logMessage);

        return $logMessage;
    }

    /**
     * Validate phone number based on Georgian national format
     * function returns corrected phone number if correction is successful
     * @param string $phone
     * 
     * @throws Exception
     * @return string
     */
    public static function getValidatedPhoneNumber(string $phone): string
    {
        if (!preg_match('/^(\+?)(\d{9}|\d{12})$/', $phone)) {
            throw new Exception("Number you provided is not in a correct format!");
        }

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
        if (!$this->getTo() || !$this->getMessage()) {
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
        if ($this->getNoSmsCode()) {
            $message .= " NO " . $this->getNoSmsCode();
        }

        $this->message = strlen($message) !== strlen(utf8_decode($message)) ? rawurlencode($message) : $message;

        return $this;
    }

    /**
     * Get supported drivers property
     * 
     * @return string
     */
    public function getSupportedDrivers(): array
    {
        return $this->supportedDrivers;
    }

    /**
     * Get from property
     * 
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * Get to property
     * 
     * @return string
     */
    public function getTo(): string
    {
        return $this->to ?? '';
    }

    /**
     * Get message property
     * 
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message ?? '';
    }
    /**
     * Get driver property
     * 
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver ?? 'log';
    }

    /**
     * Get url property
     * 
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
    /**
     * Get noSmsCode property
     * 
     * @return string
     */
    public function getNoSmsCode(): string
    {
        return $this->noSmsCode;
    }
}
