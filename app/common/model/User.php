<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;
use think\facade\Session;

use app\common\validate\User as Validate;
use app\common\validate\Login as LoginValidate;
use app\common\exception\ValidateException;

/**
 * @mixin think\Model
 */
class User extends Model
{
    public const CURRENT_KEY = 'current_user';

    /**
     * 验证字段值是否唯一
     * @Author   zhanghong(Laifuzi)
     * @param    array              $data 验证字段和字段值
     * @param    int                $id   用户ID
     * @return   bool
     */
    public static function checkFieldUnique(array $data, int $id = 0): bool
    {
        $field_name = null;
        // 验证字段名必须存在
        if (!isset($data['field'])) {
            return false;
        }
        // 验证字段名
        $field_name = $data['field'];

        // 验证字段值必须存在
        if (!isset($data[$field_name])) {
            return false;
        }
        $field_value = $data[$field_name];

        $query = static::where($field_name, $field_value);
        if ($id > 0) {
            $query->where('id', '<>', $id);
        }

        if ($query->count()) {
            return false;
        }

        return true;
    }

    /**
     * 注册新用户
     * @Author   zhanghong(Laifuzi)
     * @param    array              $data  表单提交数据
     * @param    string             $scene 验证场景名
     * @return   User                      新注册用户信息
     */
    public static function register(array $data, string $scene = 'form_register'): User
    {
        $validate = new Validate;
        if (!$validate->scene($scene)->batch(true)->check($data)) {
            $e = new ValidateException('注册数据验证失败');
            $e->setData($validate->getError());
            throw $e;
        }

        $fields = ['name', 'mobile', 'password', 'avatar'];
        if ($scene == 'seed_register') {
            array_push($fields, 'avatar');
        }

        try{
            $user = new static;
            $user->allowField($fields)->save($data);
        }catch (\Exception $e){
            throw new \Exception('创建用户失败');
        }

        return $user;
    }

    /**
     * 重置密码
     * @Author   zhanghong(Laifuzi)
     * @param    array              $data 表单提交数据
     * @return   bool
     */
    public static function resetPassword(array $data): bool
    {
        if (!isset($data['mobile'])) {
            $e = new ValidateException('重置密码验证失败');
            $e->setData(['mobile' => '注册手机号码不能为空']);
            throw $e;
        }

        $user = self::where('mobile', $data['mobile'])->find();
        if (empty($user)) {
            $e = new ValidateException('重置密码验证失败');
            $e->setData(['mobile' => '注册手机号码不在存']);
            throw $e;
        }

        $validate = new Validate;
        if (!$validate->batch(true)->scene('reset_password')->check($data)) {
            $e = new ValidateException('重置密码验证失败');
            $e->setData($validate->getError());
            throw $e;
        }

        $user->password = $data['password'];
        $is_save = $user->save();
        if (!$is_save) {
            throw new \Exception('重置密码失败');
        }
        return true;
    }

    /**
     * 密码保存时进行加密
     * @Author   zhanghong(Laifuzi)
     * @param    string             $value 原始密码
     */
    public function setPasswordAttr(string $value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * 用户登录
     * @Author   zhanghong(Laifuzi)
     * @param    string             $mobile   登录手机号码
     * @param    string             $password 登录密码
     * @return   User
     */
    public static function login(string $mobile, string $password): User
    {
        $errors = [];

        $validate = new LoginValidate;
        $data = ['mobile' => $mobile, 'password' => $password];
        if (!$validate->batch(true)->check($data)) {
            $e = new ValidateException('登录数据验证失败');
            $e->setData($validate->getError());
            throw $e;
        }

        $user = self::where('mobile', $mobile)
                    ->find();

        if (empty($user)) {
            // 传输注册手机号码不存在
            $errors['mobile'] = '注册用户不存在';
        } else if (!password_verify($password, $user->password)) {
            // 传输登录密码错误
            $errors['mobile'] = '登录手机或密码错误';
        }

        if (!empty($errors)) {
            $e = new ValidateException('登录数据验证失败');
            $e->setData($errors);
            throw $e;
        }

        // 把去除登录密码以外的信息存储到 Session 里
        unset($user['password']);
        Session::set(self::CURRENT_KEY, $user);

        return $user;
    }

    /**
     * 当前登录用户
     * @Author   zhanghong(Laifuzi)
     * @return   User
     */
    public static function currentUser()
    {
        return Session::get(self::CURRENT_KEY);
    }

    /**
     * 退出登录
     * @Author   zhanghong(Laifuzi)
     * @return   bool
     */
    public static function logout(): bool
    {
        Session::delete(self::CURRENT_KEY);
        return true;
    }

    /**
     * 更新个人资料
     * @Author   zhanghong(Laifuzi)
     * @param    array              $data 更新数据
     * @return   bool
     */
    public function updateProfile(array $data): bool
    {
        $validate = new Validate;
        if (!$validate->batch(true)->scene('update_profile')->check($data)) {
            $e = new ValidateException('更新个人信息失败');
            $e->setData($validate->getError());
            throw $e;
        }

        $is_save = $this->allowField(['name', 'introduction', 'avatar'])->save($data);
        if (!$is_save) {
            throw new \Exception('更新个人信息失败');
        }

        // 刷新用户信息
        $user = $this->refresh();
        unset($user['password']);
        Session::set(self::CURRENT_KEY, $user);

        return true;
    }
}
