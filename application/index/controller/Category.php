<?php

namespace app\index\controller;

use think\Request;

use app\common\model\Topic as TopicModel;
use app\common\model\Category as CategoryModel;

class Category extends Base
{
    public function read(Request $request, $id)
    {
        $category = CategoryModel::find($id);
        if(empty($category)){
            // 当查看分类不存在时跳转到首页
            $this->redirect('[page.root]');
        }
        $this->assign('category', $category);

        $param = $request->only(['order'], 'get');
        $param['category_id'] = $category->id;
        $paginate = TopicModel::minePaginate($param);
        $this->assign('paginate', $paginate);

        // 使用topic/index页面渲染输出
        return $this->fetch('topic/index');
    }
}
