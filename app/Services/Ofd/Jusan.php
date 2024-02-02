<?php

namespace App\Services\Ofd;

use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class Jusan
{

    public static $client;

    public static string $url;

    /**
     * @param  string  $url
     */
    public function __construct($url)
    {
        $url = Str::replaceFirst("http://", "https://", $url);
        $queryParams = [];
        parse_str(parse_url($url, PHP_URL_QUERY), $queryParams);
        if(isset($queryParams['i']) && isset($queryParams['f']) && isset($queryParams['t'])) {
            self::$url = 'https://cabinet.kofd.kz/api/tickets?registrationNumber='.$queryParams['f'].'&ticketNumber='.$queryParams['i'].'&ticketDate='.Carbon::parse($queryParams['t'])->format('Y-m-d');
        }
        self::$client = new Client([
            'allow_redirects' => false,
            'verify' => false
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
        $result = json_decode(self::$client->get(self::$url)->getBody()->getContents(), true);
        if($result['data']['found']) {
            $items = Arr::get($result, 'data.ticket');
            foreach($items as $item)
            {
                $positions[] = str($item['text'])->lower()->squish()->value();
            }
        }
        return $positions;
    }
}
