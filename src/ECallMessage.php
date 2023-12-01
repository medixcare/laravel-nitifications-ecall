<?php

namespace NotificationChannels\ECall;

use NotificationChannels\ECall\Enums\NotificationEvents;

class ECallMessage
{
    public string $channel                    = "Sms";
    public string $from                       = "";
    public string $to                         = "";
    public array  $content                    = ['type' => 'Text', 'text' => ''];
    public array  $notification               = ['addresses' => [], 'forEvent' => null];
    private int   $max_total_addresses_length = 100;

    public function to(string $to): static
    {
        $this->to = $to;

        return $this;
    }

    public function clearAddresses(): static
    {
        $this->notification['addresses'] = [];

        return $this;
    }

    public function addAdress(string $type, string $address): static
    {
        if (in_array($type, ['Email', 'Sms', 'Url', 'UrlPost'])) {
            $currentAddressLength = collect($this->notification['addresses'])
                ->map(function ($item) {
                    return strlen($item['address']);
                })
                ->sum();

            if (($currentAddressLength + strlen($address)) < $this->max_total_addresses_length) {
                $this->notification['addresses'][] = ['type' => $type, 'address' => $address];
            }
        }

        return $this;
    }

    public function hasToNumber(): bool
    {
        return !empty($this->to);
    }

    public function hasFromNumber(): bool
    {
        return !empty($this->from);
    }

    public function setNotificationType(NotificationEvents $events): static
    {
        $this->notification['forEvent'] = $events->value;

        return $this;
    }

    public function __construct(string $message)
    {
        $this->content($message);
    }

    public function content(string $message): static
    {
        $this->content['text'] = $message;

        return $this;
    }

    public function from(string $from): static
    {
        $this->from = $from;

        return $this;
    }

    public function toArray(): array
    {
        $arr = [
            'channel' => $this->channel,
            'from'    => $this->from,
            'to'      => $this->to,
            'content' => $this->content,
        ];

        if (count($this->notification['addresses']) > 0) {
            $arr['notification'] = $this->notification;
        }

        return $arr;
    }
}