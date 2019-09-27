<?php

namespace app\admin\controller;

use think\Request;
use tpadmin\controller\Controller;
use app\common\model\Reply as ReplyModel;

class Reply extends Controller
{
    public function index(Request $request)
    {
        $param = $request->param();
        $paginate = ReplyModel::adminPaginate($param);
        $this->assign('param', $param);
        $this->assign('paginate', $paginate);
        return $this->fetch('reply/index');
    }

    public function delete($id)
    {
        $reply = ReplyModel::find($id);
        if(!empty($reply)){
            $reply->delete();
        }
        return $this->success([]);
    }
}
