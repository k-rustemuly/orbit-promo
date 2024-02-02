<?php

namespace App\Services;

use Exception;
use SoapClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class IsmsApi
{
    /** @var SoapClient */
    protected $client;

    /** @var string */
    protected $endpoint;

    /** @var string */
    protected $login;

    /** @var string */
    protected $password;

    /** @var string */
    protected $from;

    /** @var array */
    const PRIORITY = [
        'low' => 1,
        'middle' => 2,
        'high' => 3,
    ];

    public function __construct(array $config)
    {
        $this->login = Arr::get($config, 'login');
        $this->password = Arr::get($config, 'password');
        $this->from = Arr::get($config, 'from');
        $this->endpoint = Arr::get($config, 'wsdl');

        $this->client = new SoapClient($this->endpoint, [
            'connection_timeout' => 1000,
            'trace' => 1,
            'exception' => 1,

            'verifypeer' => false,
            'verifyhost' => false,
            'stream_context' => stream_context_create(
            [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                ]
            ])

        ]);
    }

    /**
     * SendMessage
     *
     * @param string $phone_number
     * @param string $message
     * @return bool
     *
     * @throws Exception
     */
    public function send(string $phone_number, string $message): bool
    {
        $phone_number = preg_replace('/[^\d]/', '', $phone_number);
        // $message = str_replace('orbit-promo.kz', 'prgm.kz', $message);

        $data = [
            'login' => $this->login,
            'password' => $this->password,
            'sms' => [
                'recepient' => $phone_number,
                'senderid' => $this->from,
                'msg' => $message,
                'scheduled' => '',
                'UserMsgID' => '',
                'msgtype' => 0,
                'prioritet' => self::PRIORITY['high'],
            ]
        ];
        // Log::error(json_encode($data));

        if (app()->isLocal()) {
            logger()->debug('Send message', $data);
            return true;
        }

        $result = $this->client->SendMessage($data)->Result;

        if ($result->Status !== 'Ok') {
            throw new Exception($result->Status);
        }

        return true;
    }
}
