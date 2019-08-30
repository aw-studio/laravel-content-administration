<?php

return [

    'resource_path' => 'fjord',
    'navigation_path' => 'navigation',
    'route_prefix' => 'fjord',

    'mediaconversions' => [
        'repeatables' => [
            'sm' => [300, 300, 8],
            'md' => [500, 500, 3],
            'lg' => [900, 900, 2],
            'xl' => [1400, 1400, 1]
        ]
    ],

    'forms' => [
        'pages' => [
            // Should Pages be translatable by default.
            'translatable' => true,
            'route_prefix' => 'pages'
        ],
        'settings' => [
            'translatable' => false,
        ]
    ],

    'crud' => [

    ]
];