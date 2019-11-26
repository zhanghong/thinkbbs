<?php
declare (strict_types = 1);

namespace app\common\validate;

use think\Validate;
use think\facade\Cache;

class User extends Validate
{
    protected $rule = [
        'name' => 'require|length:2,20',
        'mobile' => 'require|between:13000000000,19900000000|unique:user',
        'password' => 'require|length:6,20',
        'password_confirmation' => 'require|length:6,20|confirm:password',
        'sms_code' => 'require|length:6|checkCode',
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
        'sms_code.require' => '短信验证码不能为空',
        'sms_code.length' => '短信验证码不正确',
        'sms_code.filter_sms_code' => '短信验证码不正确',
    ];

    /**
     * 自定义验证方法-验证用户输入的短信验证码是否正确
     * @Author   zhanghong(Laifuzi)
     * @param    string             $value 字段值
     * @param    string             $rule  字段值验证值
     * @param    array              $data  表单提交的所有数据
     * @return   string/true               验证结果
     */
    public function checkCode($value, $rule, $data = [])
    {
        $invalid_msg = '短信验证码不正确';
        if (!isset($data['mobile'])) {
            return $invalid_msg;
        }

        $mobile = $data['mobile'];
        $cache_code = Cache::store('redis')->get($mobile);
        if (empty($cache_code)) {
            return $invalid_msg;
        } else if ($value != $cache_code) {
            return $invalid_msg;
        }
        return true;
    }
}
