<?php

namespace app\index\controller;

use think\Request;
use app\common\model\User as UserModel;

class User extends Base
{
    public function read($id)
    {
        $user = UserModel::find(intval($id));
        if(empty($user)){
            // 当查看的用户不存在时跳转到首页
            $this->redirect('[page.root]');
        }

        $this->assign('user', $user);
        return $this->fetch('read');
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }
}