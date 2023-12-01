<?php

namespace NotificationChannels\ECall\Exceptions;

class UnableToSendNotification extends \Exception
{
    public static function apiKeyNotProvided(): self
    {
        return new static('API key is missing.');
    }

    public static function serviceNotAvailable($message): self
    {
        return new static($message);
    }

    public static function invalidSendingNumber(): self
    {
        return new static('Provided number used for sending is not valid.');
    }

    public static function serviceRespondedWithAnError($message): self
    {
        return new static('ECall Response: ' . $message);
    }

    public static function phoneNumberNotProvided(): self
    {
        return new static('No phone number was provided.');
    }

    public static function eCallMethodNotImplementedOnNotification(): self
    {
        return new static('Method toEcall is missing on Notification.');
    }

    public static function invalidMessageType(): self
    {
        return new static('Message is not an instance of ECallMessage.');
    }
}