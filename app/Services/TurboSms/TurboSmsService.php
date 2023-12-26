<?php

namespace App\Services\TurboSms;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TurboSmsService
{
    const SUCCESS_MESSAGE_ACCEPTED = 'SUCCESS_MESSAGE_ACCEPTED';

    protected string $url;
    protected string $token;
    protected Client $client;

    public function __construct()
    {
        $this->token = 'Basic typeAPIkeyHere';
        $this->url = 'https://api.turbosms.ua/message/send.json';
        $this->client = new Client();
    }

    public function sendSms($phone, $message): string
    {
        try {
            $response = $this->client->post($this->url, [
                'headers' =>
                    [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Authorization' => $this->token
                    ],
                'form_params' => $this->arrayParams([$phone], $message)
            ]);

            return $response->getBody()->getContents();
        } catch (RequestException $requestException) {
            return $requestException->getMessage();
        }
    }

    protected function arrayParams($phone, $message): array
    {
        return [
            'recipients' => $phone,
            'sms' => [
                'sender' => 'IT Alarm',
                'text' => $message
            ]
        ];
    }
}
