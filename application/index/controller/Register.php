<?php

namespace app\index\controller;

use think\Request;
use app\common\model\User;

class Register extends Base
{
    public function create()
    {
        return $this->fetch('create');
    }

    public function save(Request $request)
    {
        // 实例化一个User对象
        $user = new User;
        // 保存表单提交数据
        $user->save($request->post());
        $message = '注册成功';
        // 注册成功后跳转到注册表单页面
        $this->success($message, url('[page.root]'));
    }
}
