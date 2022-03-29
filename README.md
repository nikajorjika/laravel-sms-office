# Laravel SMS Office

_nikajorjika/laravel-sms-office_ is a support package for [smsoffice.ge](https://smsoffice.ge/).

## Installation

Use the package manager [pip](https://pip.pypa.io/en/stable/) to install foobar.

```bash
composer require nikajorjika/laravel-sms-office
```

### Adding Variables in _.env_ file

```
# for testing purposes you can also use SMS_OFFICE_DRIVE=log
SMS_OFFICE_DRIVER=sms-office
SMS_OFFICE_API_KEY=[api-key-provided-by-smsoffice.ge]
SMS_OFFICE_SENDER=[sender-name]
SMS_OFFICE_NOSMS=[no-sms-code-provided-by-smsoffice.ge]
```

## Usage

There are two ways to use this package.

#### As a Facade

```php
<?php
// Basic Usage
...
use Nikajorjika\SmsOffice\Facades\SmsOffice;

$phoneNumber = '855737812'; // It could also be 995855737812
$message = 'You have found your package ;).';

SmsOffice::message($message)->to($phoneNumber)->send();

```

#### As a Channel

To start off, we need to include **SmsOfficeChannel::class** in via channels array and implement our version of **toSms** function as shown below.

```php
<?php
...
use Nikajorjika\SmsOffice\SmsOfficeChannel;

class FooBarNotification extends Notification implements ShouldQueue
{
    use Queueable;

    ...

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsOfficeChannel::class];
    }

    /**
     * Return message to send via SmsOffice Channel
     *
     * @param mixed $notifiable
     * @return string $message
     */
    public function toSms($notifiable)
    {
        return 'You have found your package ;).';
    }

```

and inside our notifiable model, **User** in this case, we should implement **routeNotificationForSms** method, like so:

```php
...
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Get phone number from notifiable model
     *
     * @return string
     */
    public function routeNotificationForSms()
    {
        return $this->full_phone_number;
    }

```

that's it now we will be able to send notifications via additional SmsOffice channel.

## Package Configuration

To further customize and configure package we first need to publish our config file:

```bash
php artisan vendor:publish --provider="Nikajorjika\SmsOffice\SmsOfficeServiceProvider" --tag="config"
```

this will publish **smsoffice.php** file inside our **config** folder:

```php
<?php

return [
    /**
     * Endpoint for sms office url
     */
    'api_url' => env('SMS_OFFICE_URL', 'http://smsoffice.ge/api/v2/send/'),

    /**
     * Private Key provided by sms office service
     */
    'key' => env('SMS_OFFICE_KEY', null),

    /**
     * Driver that serves as a channel driver for laravel
     */
    'driver' => env('SMS_OFFICE_DRIVER', 'sms-office'),

    /**
     * This key defines sender name
     * for the sms to be delivered from
     */
    'from' => env('SMS_OFFICE_FROM', NULL),

    /**
     * List of drivers that sms office package supports
     */
    'supported_drivers' => ['sms-office', 'log'],

    /**
     * Define no sms code for the user to unsubscribe
     */
    'no_sms_code' => env('SMS_OFFICE_NOSMS', NULL),

];

```

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)
