<?php
declare (strict_types = 1);

namespace app\admin\controller;

use tpadmin\controller\Controller;
use app\common\model\User as UserModel;

class User extends Controller
{
    public function index()
    {
        $param = $this->request->param();
        return $this->fetch('user/index', [
            'param' => $param,
            'paginate' => UserModel::adminPaginate($param),
        ]);
    }

    public function delete($id)
    {
        $user = UserModel::find($id);
        if (!empty($user)) {
            $user->delete();
        }
        return $this->success('删除成功');
    }
}
