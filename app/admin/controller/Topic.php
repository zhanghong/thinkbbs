<?php
declare (strict_types = 1);

namespace app\admin\controller;

use tpadmin\controller\Controller;
use app\common\model\Topic as TopicModel;
use app\common\model\Category as CategoryModel;

class Topic extends Controller
{
    public function index()
    {
        $param = $this->request->param();
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
