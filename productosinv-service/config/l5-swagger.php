<?php

return [
    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'API Productos',
            ],

            'routes' => [
                // 🔹 Aquí se sirve el JSON crudo
                'api'  => 'api/documentation',

                // 🔹 Aquí se sirve la interfaz Swagger UI
                'docs' => 'docs',
            ],

            'paths' => [
                'docs_json' => 'api-docs.json',
                'format_to_use_for_docs' => 'json',
                'docs' => storage_path('api-docs'),
                'annotations' => [
                    base_path('app'),
                ],
            ],

            // 🔹 Esta clave la borraste y es obligatoria
            'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
            'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),
        ],
    ],

    'defaults' => [
        'routes' => [
            'docs' => 'docs',
            'oauth2_callback' => 'api/oauth2-callback',
            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],
            'group_options' => [],
        ],

        'paths' => [
            'docs' => storage_path('api-docs'),
            'views' => base_path('resources/views/vendor/l5-swagger'),
            'base' => env('L5_SWAGGER_BASE_PATH', null),
            'excludes' => [],
        ],
    ],
];
