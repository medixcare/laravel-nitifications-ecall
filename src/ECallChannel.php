<?php

namespace NotificationChannels\ECall;


use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Notifications\Notification;
use NotificationChannels\ECall\Enums\NotificationEvents;
use NotificationChannels\ECall\Exceptions\UnableToSendNotification;

class ECallChannel
{
    /**
     * @var ECall
     */
    protected ECall $ECall;

    /**
     * @param ECall $ECall
     */
    public function __construct(ECall $ECall)
    {
        $this->ECall = $ECall;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return array
     * @throws GuzzleException
     * @throws UnableToSendNotification
     */
    public function send(mixed $notifiable, Notification $notification): array
    {
        if (!method_exists($notification, 'toECall')) {
            throw UnableToSendNotification::eCallMethodNotImplementedOnNotification();
        }

        $message = $notification->toECall($notifiable);
        if (is_string($message)) {
            $message = new ECallMessage($message);
        }

        if (!$message->hasToNumber()) {
            if (!$to = $notifiable->phone_number) {
                $to = $notifiable->routeNotificationFor('sms');
            }
            if (!$to) {
                throw UnableToSendNotification::phoneNumberNotProvided();
            }

            $message->to($to);
        }

        if (!$message->hasFromNumber()) {
            $message->from(config('services.ecall.from'));
        }

        $params = $message->setNotificationType(NotificationEvents::ALL)->toArray();

        $response = $this->ECall->sendMessage($params);

        return json_decode($response->getBody()->getContents(), true);
    }
}