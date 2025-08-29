<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'inventario' => [
        'api_key' => env('INVENTARIO_API_KEY', 'secret123'),
    ],

    'productos' => [
        'base_url' => env('PRODUCTOS_SERVICE_URL', 'http://microservicios-productos:8000'),
        'api_key'  => env('PRODUCTOS_API_KEY', 'secret123'),
    ],

];
