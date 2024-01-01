<?php

return [
    'api_key'       => env('RAWG_API_KEY'),
    'host'          => 'https://api.rawg.io/api',
    'routes'        => [
        'games'  => 'games',
        'genres' => 'genres',
    ],
    'games_filters' => [
        'platforms' => '18,187',
        'ordering'  => '-created',
        'page_size' => 25,
    ],
];
