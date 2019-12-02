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
        if (!$request->isPost() || !$request->isAjax()) {
            return $this->error('对不起，你访问页面不存在。');
        }

        try {
            // 保存表单提交数据
            $user = new User;
            $user->save($request->post());
        } catch (\Exception $e) {
            return $this->error('对不起，注册失败。');
        }

        // 注册成功后跳转到首页
        return $this->success('恭喜你注册成功。', '/');
    }

    /**
     * 验证字段值是否唯一
     * @Author   zhanghong(Laifuzi)
     */
    public function check_unique(Request $request)
    {
        if(!$request->isAjax()){
            return $this->redirect('[page.signup]');
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
