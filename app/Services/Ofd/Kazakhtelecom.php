<?php

namespace App\Services\Ofd;

use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class Kazakhtelecom
{

    public static $client;

    public static string $url;

    /**
     * @param  string  $url
     */
    public function __construct($url)
    {
        self::$url = Str::replaceFirst("http://", "https://", $url);
        self::$client = new Client([
            'allow_redirects' => false,
        ]);
    }

    /**
     *
     * @param  string $url
     *
     * @return static
     */
    public static function make(string $url = '')
    {
        return new static($url);
    }

    public static function positions(): array
    {
        $positions = [];
        if(app()->isLocal()) {
            self::$url = 'https://consumer.oofd.kz/api/tickets/ticket/da18d1a8-760c-4308-b8ca-69052ebf2514';
        }
        else {
            $response = self::$client->get(self::$url);
            $location = $response->getHeader('Location')[0] ?? null;
            self::$url = 'https://consumer.oofd.kz/api/tickets'.$location;
        }
        $result = json_decode(self::$client->get(self::$url)->getBody()->getContents(), true);
        $items = Arr::get($result, 'ticket.items');
        foreach($items as $item)
        {
            $positions[] = str($item['commodity']['name'])->lower()->squish()->value();
        }
        return $positions;
    }
}
