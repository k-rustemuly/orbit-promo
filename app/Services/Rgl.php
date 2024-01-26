<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

class Rgl
{
    /** @var Client */
    protected $client;

    /** @var string */
    protected $endpoint;

    /** @var string */
    protected $key;

    /** @var string */
    protected $promoId;

    public function __construct(array $config, public $container = [])
    {
        $this->key = Arr::get($config, 'key');
        $this->promoId = Arr::get($config, 'promo_id');

        $stack = HandlerStack::create();
        $history = Middleware::history($container);
        $stack->push($history);
        $this->client = new Client([
            'verify' => false,
            'handler' => $stack
        ]);
    }

    /**
     * Send Response
     *
     * @param string $phone_number
     * @param string $promo_code
     * @return bool
     *
     * @throws Exception
     */
    public function send(string $phone_number, string $promo_code): bool
    {
        $headers = [
            'x-api-key' => $this->key,
            'Content-Type' => 'application/json'
        ];
        $body = [
            'phone' => $phone_number,
            'promo_code' => $promo_code,
        ];

        $parsedUrl = parse_url(config('app.url'));
        if ($parsedUrl && isset($parsedUrl['host'])) {
            $zone = Str::afterLast($parsedUrl['host'], '.');
            if($zone == 'kz') {
                try {
                    $request = new Request('POST', 'https://api.rgl.su/v4/'.$this->promoId.'/code/register', $headers, json_encode($body));
                    $res = $this->client->sendAsync($request)->wait();
                    $body = $res->getBody()->getContents();
                    $jsonResponse = json_decode($body, true);
                    logger()->debug($jsonResponse);
                } catch (RequestException $e) {
                    if ($e->hasResponse()) {
                        $response = $e->getResponse();
                        $statusCode = $response->getStatusCode();
                        $body = $response->getBody()->getContents();
                        logger()->debug($response. ' '.$statusCode. ' ' .$body);
                    } else {
                        $message = $e->getMessage();
                        logger()->debug($message);
                    }
                } catch (\Exception $e) {
                    $message = $e->getMessage();
                    logger()->debug($message);
                }
            }
        }
        return true;
    }
}
