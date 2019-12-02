<?php

namespace app\common\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'name' => 'require|length:2,20',
        'mobile' => 'require|between:13000000000,19900000000|unique:user',
        'password' => 'require|length:6,20',
        'password_confirmation' => 'require|length:6,20|confirm:password',
    ];

    protected $message = [
        'name.require' => '用户名不能为空',
        'name.length' => '用户名长度必须在2-20个字符之间',
        'mobile.require' => '手机号码不能为空',
        'mobile.between' => '手机号码格式不正确',
        'mobile.unique' => '当前手机号码已注册',
        'password.require' => '登录密码不能为空',
        'password.length' => '登录密码长度必须在6-20之间',
        'password_confirmation.require' => '重复密码不能为空',
        'password_confirmation.length' => '重复密码长度必须在6-20之间',
        'password_confirmation.confirm' => '两次输入的密码不一致',
    ];
}
