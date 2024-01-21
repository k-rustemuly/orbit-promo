<?php

namespace App\Services;

use App\Exceptions\CouldNotSendNotification;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;

class GetSmsApi
{
    /** @var HttpClient */
    protected $client;

    /** @var string */
    protected $endpoint;

    /** @var string */
    protected $login;

    /** @var string */
    protected $password;

    public function __construct(array $config)
    {
        $this->login = Arr::get($config, 'login');
        $this->password = Arr::get($config, 'password');
        $this->endpoint = Arr::get($config, 'host').'smsgateway/';


        $this->client = new HttpClient([
            'timeout' => 5,
            'connect_timeout' => 5,
            'verify' => false
        ]);
    }

    public function send($params)
    {
        $base = [
            'login' => $this->login,
            'password' => $this->password,
            'data' => json_encode([$params])
        ];

        try {
            $request = new Request('POST', $this->endpoint, body: $base);
            $response = $this->client->sendAsync($request)->wait();
            $response = \json_decode((string) $response->getBody(), true);

            if (isset($response['error'])) {
                throw new \DomainException($response['error_text'], $response['error']);
            }
            // Log::debug(json_encode($response));

            return $response;
        } catch (\DomainException $exception) {
            throw CouldNotSendNotification::smscRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithSmsc($exception);
        }
    }
}
