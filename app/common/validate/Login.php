<?php
declare (strict_types = 1);

namespace app\common\validate;

use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'mobile' => 'require|length:11',
        'password' => 'require',
    ];

    protected $message = [
        'mobile.require' => '手机号码不能为空',
        'mobile.length' => '手机号码不正确',
        'password.require' => '登录密码不能为空',
    ];
}
