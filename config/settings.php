<?php

use App\Services\Ofd\Kazakhtelecom;
use App\Services\Ofd\Transtelecom;

return [
    'ofd_domains' => [
        'ofd1.kz' => Transtelecom::class,
        'consumer.oofd.kz' => Kazakhtelecom::class,
        'ofd.soliq.uz' => '3',
    ],
];
