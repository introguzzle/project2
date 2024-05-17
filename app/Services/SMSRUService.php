<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Env;

class SMSRUService
{
    protected const string ENDPOINT = 'https://sms.ru/sms/send';
    protected string $token;
    protected string $sender;

    private static Client $client;

    /**
     * @param string|null $token
     * @param string|null $sender
     */
    public function __construct(
        ?string $token = null,
        ?string $sender = null
    )
    {
        $this->token = $token ?? Env::get('SMS_API_TOKEN');
        $this->sender = $sender ?? Env::get('SMS_API_SENDER');

        if (!isset(self::$client)) {
            self::$client = new Client(['base_uri' => self::ENDPOINT]);
        }
    }

    /**
     * @throws GuzzleException
     */
    public function sendSMS(
        string $to,
        string $message
    ): string
    {
        $response = self::$client->request('GET', '', [
            'query' => [
                'api_id' => $this->token,
                'to' => $to,
                'msg' => $message,
                'json' => 1,
                'from' => $this->sender
            ]
        ]);

        return $response->getBody()->getContents();
    }
}
