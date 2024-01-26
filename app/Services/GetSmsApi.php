<?php

namespace App\Services;

use App\Exceptions\CouldNotSendNotification;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Zadarma_API\Api;

class GetSmsApi
{
    /** @var \Zadarma_API\Api */
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
        $this->endpoint = Arr::get($config, 'host').'v1/sms/send';

        $this->client = new Api($this->login, $this->password, app()->isLocal());
    }

    public function send(string $phone_number, string $message): bool
    {
        try{
            $sms = $this->client->sendSms($phone_number, $message);
            return true;
        } catch (\Zadarma_API\ApiException) {
            Log::error("Zadarma Api error: ($phone_number, $message) - ". json_encode($sms->toArray()));
        }
        return false;
    }
}
