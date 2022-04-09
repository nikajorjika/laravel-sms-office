<?php

namespace Nikajorjika\SmsOffice\Contracts;

use Exception;

interface SmsOffice
{

    /**
     * Get Sevice final url string
     * 
     * @param string $destination
     * @param string $message
     * 
     * @return string
     */
    public function send(): string;

    /**
     * Get Sevice final url string
     * 
     * @param string $destination
     * @param string $message
     * 
     * @return string
     */
    public function getServiceUrl(string $destination): string;

    /**
     * Get query string with replaced variables according to SMS Office documentation
     * @param string $destination
     * @param string $message 
     * 
     * @return string
     */
    public function getReplacedQueryString(string $destination): string;


    /**
     * Generates log for faking sms functionality without sending actual sms
     * 
     * @return string
     */
    public function logMessage(string $destination): string;

    /**
     * Validate phone number based on Georgian national format
     * function returns corrected phone number if correction is successful
     * @param string $phone
     * 
     * @return string
     */
    public static function getValidatedPhoneNumber(string $phone): string;

    /**
     * Check if all the parameters required for the package is actually provided
     * it will throw an error if any parameters are missing from the config file
     * 
     * @param string $phone
     * 
     * @throws Exception
     * @return bool
     */
    public function validateConfigParams(): bool;

    /**
     * Check if all the nessesary sms office params are set
     * 
     * @throws Exception
     * @return bool
     */
    public function validateSmsParams(): bool;

    /**
     * Get parameter is missing exception with generic text
     * @param string $parameterName
     * 
     * @return Exception
     */
    public function getMissingParamException(string $parameterName): Exception;

    /**
     * Set 'dryRun' property to given value
     * @param string $dry
     * 
     * @return self
     */
    public function dryrun(string $dry = 'yes'): self;


    /**
     * Set 'from' property to given value
     * @param string $dry
     * 
     * @return self
     */
    public function from($from): self;

    /**
     * Set 'to' property to given value
     * @param string $to
     * 
     * @return self
     */
    public function to(string $to): self;

    /**
     * Set 'message' property to given value
     * @param string $dry
     * 
     * @return self
     */
    public function message($message): self;


    /**
     * Get supported locales property
     * 
     * @return string
     */
    public function getSupportedDrivers(): array;

    /**
     * Get supported from property
     * 
     * @return string
     */
    public function getFrom(): string;

    /**
     * Get supported to property
     * 
     * @return string
     */
    public function getTo(): string;

    /**
     * Get supported message property
     * 
     * @return string
     */
    public function getMessage(): string;

    /**
     * Get driver property
     * 
     * @return string
     */
    public function getDriver(): string;

    /**
     * Get url property
     * 
     * @return string
     */
    public function getUrl(): string;

    /**
     * Get noSmsCode property
     * 
     * @return string
     */
    public function getNoSmsCode(): string;
}
