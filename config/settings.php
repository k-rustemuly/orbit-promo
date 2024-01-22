<?php

use App\Services\Ofd\Kazakhtelecom;

return [
    'ofd_domains' => [
        'ofd1.kz' => '1',
        'consumer.oofd.kz' => Kazakhtelecom::class,
        'ofd.soliq.uz' => '3',
    ],
];
