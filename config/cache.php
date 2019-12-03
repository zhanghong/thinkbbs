<?php
return [
    // 使用复合缓存类型
    'type'  =>  'complex',
    // 默认使用的缓存
    'default'   =>  [
        // 全局缓存有效期（0为永久有效）
        'expire'=>  0,
        // 驱动方式
        'type'   => 'file',
        // 缓存保存目录
        'path'   => '../runtime/default',
    ],
    // 文件缓存
    'file'   =>  [
        // 驱动方式
        'type'   => 'file',
        // 设置不同的缓存保存目录
        'path'   => '../runtime/file/',
    ],
    // redis缓存
    'redis'   =>  [
        // 驱动方式
        'type'   => 'redis',
        // 全局缓存有效期（0为永久有效）
        'expire'=>  env('cache.redis_expire', 0),
        // 缓存前缀
        'prefix'=>  env('cache.redis_prefix', 'think_'),
        // 服务器地址
        'host'       => env('cache.redis_host', '127.0.0.1'),
        'port'       => env('cache.redis_port', 6379),
    ],
];
