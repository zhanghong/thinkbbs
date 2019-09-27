<?php

namespace app\admin\controller;

use tpadmin\controller\Controller;
use think\Request;
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
        return $this->success([]);
    }
}