<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sidebar menu configuration
    |--------------------------------------------------------------------------
    |
    */

    'menu' => [
        [
            'type' => 'item',
            'label' => 'Bảng tin',
            'route' => 'admin.index',
            'icon' => 'fas fa-tachometer-alt'
        ],
        [
            'type' => 'header',
            'label' => 'CÀI ĐẶT HỆ THỐNG'
        ],
        [
            'type' => 'item',
            'label' => 'Cài đặt',
            'route' => 'admin.settings.index',
            'icon' => 'fas fa-cog',
            'sub' => [
                [
                    'label' => 'Cài đặt chung',
                    'route' => 'admin.settings.index'
                ]
            ]
        ]
    ]

];
