# ECall Notifications channel for laravel

This package makes it easy to send notifications using [ECall Messaging](https://ecall-messaging.com/) with Laravel.

[API Documentation](https://ecall-messaging.com/en/interfaces-and-documents/rest-interface/)

## Contents

- [Installation](#installation)
    - [Setting up the ECall service](#setting-up-the-ECall-service)
- [Usage](#usage)
    - [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [License](#license)

## Installation

### Setting up the ECall service

1. Create an account [here](https://portal.ecall-messaging.com/registration/)
2. Create Sub-User in the ECall-Portal (Interfaces -> User)
3. Add credentials to the `services.php` config file:

```php
// config/services.php
...
'ecall' => [
    'username' => env('ECALL_USERNAME'),
    'password' => env('ECALL_PASSWORD'),
    'from'     => env('ECALL_FROM'),
]
...
```

## Usage

You can use this channel by adding `ECallChannel::class` to the array in the `via()` method of your notification class.
You need to add the `toECall()` method which should return a `new ECallMessage()` object.

```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\ECall\ECallChannel;
use NotificationChannels\ECall\ECallMessage;

class InvoicePaid extends Notification
{
    public function via($notifiable)
    {
        return [ECallChannel::class];
    }

    public function toECall() {
        return (new ECallMessage('Hallo!'))
        ->from('chilly');
    }
}
```

## Available Message methods

- `content(string $message)`: Sets SMS message Text.
- `to(string $number)`: Set recipients number.
- `from(string $number)`: Set senders name or number.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.