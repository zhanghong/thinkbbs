<?php

namespace app\admin\controller;

use think\Request;
use tpadmin\controller\Controller;
use app\common\model\Topic as TopicModel;
use app\common\model\Category as CategoryModel;

class Topic extends Controller
{
    public function index(Request $request)
    {
        $param = $request->param();
        return $this->fetch('topic/index', [
            'param' => $param,
            'categories' => CategoryModel::select(),
            'paginate' => TopicModel::adminPaginate($param),
        ]);
    }

    public function delete($id)
    {
        $topic = TopicModel::find($id);
        if (!empty($topic)) {
            $topic->delete();
        }
        return $this->success('删除成功');
    }
}
