<?php

return [
    'pagination_limit'      => 15,
    'pagination_limit_api'  => 10,
    'show_page_limit'       => 5,
    'home_page_limit'       => 5,
    'message_limit'         => 7,
    'formatted_datetime'    => 'd M, y - h:i A',

    //Dynamic
    'stripe' => [
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'api_key'    => env('API_KEY'),
];
