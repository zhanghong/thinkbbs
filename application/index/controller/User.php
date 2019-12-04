<?php

namespace app\index\controller;

use think\Request;
use app\common\model\User as UserModel;

class User extends Base
{
    public function read($id)
    {
        $user = UserModel::find(intval($id));
        if (empty($user)) {
            // 当查看的用户不存在时跳转到首页
            $message = '对不起，你访问页面不存在。';
            Session::flash('danger', $message);
            return $this->redirect('[page.root]');
        }

        return $this->fetch('read', ['user' => $user]);
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
