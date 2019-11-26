<?php
declare (strict_types = 1);

namespace app\index\controller;

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
            return $this->redirect('/');
        }

        return $this->fetch('read', ['user' => $user]);
    }

    public function edit()
    {
        //
    }

    public function update()
    {
        //
    }
}
