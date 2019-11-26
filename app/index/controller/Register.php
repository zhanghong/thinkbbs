<?php
declare (strict_types = 1);

namespace app\index\controller;

use app\common\model\User;

class Register extends Base
{
    public function create()
    {
        return $this->fetch('create');
    }

    public function save(User $user)
    {
        // 保存表单提交数据
        $user->save($this->request->post());
        // 注册成功后跳转到首页
        return $this->redirect('/');
    }
}
