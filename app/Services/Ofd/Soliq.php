<?php

namespace App\Services\Ofd;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class Soliq
{

    public static $client;

    public static string $url;

    /**
     * @param  string  $url
     */
    public function __construct($url)
    {
        self::$url = $url;
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
        $html = self::$client->get(self::$url)->getBody()->getContents();
        $crawler = new Crawler($html);
        $crawler->filter('table.products-tables > tbody > tr.products-row > td:first-child')->each(function (Crawler $crawler) use(&$positions) {
            $positions[] = str($crawler->html())->squish()->lower()->value();
        });
        return $positions;
    }
}
