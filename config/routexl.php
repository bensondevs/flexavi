<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default RouteXL Configuration
    |--------------------------------------------------------------------------
    */
    'base_url' => env('ROUTEXL_API_URL', 'https://api.routexl.com/'),

    'max_location_per_request' => 10,

    'auth' => [
        'auth_type' => env('ROUTEXL_AUTH_TYPE', 'Basic'),
        'username' => env('ROUTEXL_USERNAME', 'martien_marketing'),
        'password' => env('ROUTEXL_PASSWORD', 'Welcome.123'),
    ],


];
