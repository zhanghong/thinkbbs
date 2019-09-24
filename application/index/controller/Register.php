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
        $this->success($message, url('create'));
    }

    /**
     * 验证字段值是否唯一
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-09-24
     */
    public function check_unique(Request $request)
    {
        if(!$request->isAjax()){
            $this->redirect('[page.signup]');
        }

        $param = $request->post();
        $is_valid = User::checkFieldUnique($param);
        if($is_valid){
            echo("true");
        }else{
            echo("false");
        }
    }
}