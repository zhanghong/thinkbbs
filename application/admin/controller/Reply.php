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
        return $this->fetch('reply/index', [
            'param' => $param,
            'paginate' => ReplyModel::adminPaginate($param),
        ]);
    }

    public function delete($id)
    {
        $reply = ReplyModel::find($id);
        if (!empty($reply)) {
            $reply->delete();
        }
        return $this->success('删除成功');
    }
}
