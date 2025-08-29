<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'routes' => [
                'docs' => 'docs',             // <- esto carga la UI
                'api' => 'api/documentation', // <- esto devuelve JSON
            ],
            'paths' => [
                'docs_json' => 'api-docs.json',
                'format_to_use_for_docs' => 'json',
                'docs' => storage_path('api-docs'),
                'annotations' => [
                    base_path('app'),
                ],
            ],
        ],
    ],
];
