<?php

return [
    'memcached' => [
        'host' => false === getenv('MEMCACHED_HOST') ? 'memcached' : getenv('MEMCACHED_HOST'),
        'port' => false === getenv('MEMCACHED_PORT') ? 11211 : (int)getenv('MEMCACHED_PORT'),
    ],
];
