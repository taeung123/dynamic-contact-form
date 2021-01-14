<?php
return [
    'auth_middleware' => [
        'admin'    => [
            'middleware' => '', // auth.jwt
            'except'     => [],
        ],
        'frontend' => [
            'middleware' => '',
            'except'     => [],
        ],
    ],
    // 'page'            => [
    //     'contact' => [
    //         'label'    => 'Liên hệ',
    //         'position' => [
    //             'position-1' => 'Cột ngoài cùng bên trái',
    //             'position-2' => 'Vị trí chính',
    //             'position-3' => 'Vị trí bên trên bản đồ',
    //             'position-4' => 'Cột ngoài cùng bên phải',
    //         ],
    //     ],
    // ],
];
