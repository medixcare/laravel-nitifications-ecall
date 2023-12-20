<?php

namespace NotificationChannels\ECall;


use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use NotificationChannels\ECall\Exceptions\UnableToSendNotification;

class ECall
{
    /**
     * @var string ECall API URL.
     */
    protected string $apiUrl = 'https://rest.ecall.ch/api/message';

    protected HttpClient $http;

    /**
     * @var null|string ECall API Key.
     */
    protected string $apiKey;
    protected string $from;

    /**
     * @throws UnableToSendNotification
     */
    public function __construct(
        string $username = null,
        string $password = null,
        string $from = null,
        HttpClient $client = null
    ) {
        $this->http = $client;

        if (str($from)->startsWith('+')) {
            $from = str($from)->replace('+', '00');
        }

        if (is_numeric($from) && strlen($from) > 16 || !is_numeric($from) && strlen($from) > 11) {
            throw UnableToSendNotification::invalidSendingNumber();
        }

        $this->from   = $from;
        $this->apiKey = base64_encode(sprintf('%s:%s', $username, $password));
    }

    /**
     * Get API key.
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Set API key.
     *
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Get HttpClient.
     *
     * @return HttpClient
     */
    protected function httpClient(): HttpClient
    {
        return $this->http ?? new HttpClient();
    }

    /**
     * @throws UnableToSendNotification|\GuzzleHttp\Exception\GuzzleException
     */
    public function sendMessage(array $params): \Psr\Http\Message\ResponseInterface
    {
        if (empty($this->apiKey)) {
            throw UnableToSendNotification::apiKeyNotProvided();
        }

        if (empty($params['from'])) {
            $params['from'] = $this->from;
        }

        $headers = [
            'Authorization' => 'Basic ' . $this->apiKey,
            'Content-Type'  => 'application/json'
        ];
        $body    = json_encode($params);

        $request = new Request('POST', $this->apiUrl, $headers, $body);
        try {
            return $this->httpClient()->send($request);
        } catch (ClientException $exception) {
            throw UnableToSendNotification::serviceRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw UnableToSendNotification::serviceNotAvailable($exception);
        }
    }
}