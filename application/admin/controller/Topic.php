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
        $paginate = TopicModel::adminPaginate($param);
        $this->assign('param', $param);
        $this->assign('categories', CategoryModel::all());
        $this->assign('paginate', $paginate);
        return $this->fetch('topic/index');
    }

    public function delete($id)
    {
        $topic = TopicModel::find($id);
        if(!empty($topic)){
            $topic->delete();
        }
        return $this->success([]);
    }
}
