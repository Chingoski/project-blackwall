<?php

return [
    'api_key'       => env('RAWG_API_KEY'),
    'host'          => 'https://api.rawg.io/api',
    'routes'        => [
        'games'  => 'games',
        'tags'   => 'tags',
        'genres' => 'genres',
    ],
    'games_filters' => [
        'platforms' => '18,187',
        'ordering'  => '-released',
        'page_size' => 25,
    ],
];
