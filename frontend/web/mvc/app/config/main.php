<?php

return [
    'router' => [
        'routs' => [
            'site/index' => [
                'method' => 'get',
            ],
            'site/add' => [
                'method' => 'post',
            ],
            'site/get' => [
                'method' => 'get',
            ],
            'site/store' => [
                'method' => 'post',
            ],

            'admin/index' => [
                'method' => 'get',
            ],
            'admin/login' => [
                'method' => 'post',
            ],
            'admin/logout' => [
                'method' => 'get',
            ],
        ],
        'default' => 'site/index',
    ]
];
