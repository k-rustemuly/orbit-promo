<?php

use Illuminate\Support\Str;

if (!function_exists('region')) {
    function region() {
        $parsedUrl = parse_url(config('app.url'));
        if ($parsedUrl && isset($parsedUrl['host'])) {
            return Str::afterLast($parsedUrl['host'], '.');
        }
        return 'kz';
    }
}
