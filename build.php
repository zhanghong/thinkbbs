<?php

return [
    // 定义共公模块的自动生成
    'common'     => [
        '__file__'   => [],
        '__dir__'    => ['exception', 'model', 'observer', 'validate'],
        'controller' => [],
        'model'      => [],
        'view'       => [],
    ],

    // 定义前台模块的自动生成
    'index'     => [
        '__file__'   => [],
        '__dir__'    => ['config', 'controller'],
        'controller' => ['Index'],
        'model'      => [],
        'view'       => ['index/index'],
    ],

    // 定义后台模块的自动生成
    'admin'     => [
        '__file__'   => [],
        '__dir__'    => ['config', 'controller'],
        'controller' => ['Index'],
        'model'      => [],
        'view'       => [],
    ],
];
