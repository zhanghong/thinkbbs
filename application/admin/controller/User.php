<?php

namespace app\admin\controller;

use think\Request;
use tpadmin\controller\Controller;
use app\common\model\User as UserModel;

class User extends Controller
{
    public function index(Request $request)
    {
        $param = $request->param();
        $paginate = UserModel::adminPaginate($param);
        $this->assign('param', $param);
        $this->assign('paginate', $paginate);
        return $this->fetch('user/index');
    }

    public function delete($id)
    {
        $user = UserModel::find($id);
        if(!empty($user)){
            $user->delete();
        }
        return $this->success('删除成功');
    }
}
