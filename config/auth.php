<?php
/**
 * Tpadmin 配置.
 *
 * 该配置为默认配置
 * 如在thinkphp框架中config目录下的tpadmin.php中的配置优先级高于此配置
 */
return [
    // auth配置
    'auth_on'           => 1, // 权限开关
    'auth_type'         => 1, // 认证方式，1为实时认证；2为登录认证。
    'auth_user'         => 'tpadmin\model\adminer', // 用户信息不带前缀表名
];