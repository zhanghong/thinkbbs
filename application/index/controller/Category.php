<?php

namespace app\index\controller;

use app\common\model\Topic as TopicModel;
use app\common\model\Category as CategoryModel;

class Category extends Base
{
    public function read($id)
    {
        $category = CategoryModel::find($id);
        if (empty($category)) {
            // 当查看分类不存在时跳转到首页
            return $this->redirect('[page.root]');
        }

        $param = [
            'category_id' => $category->id,
        ];

        return $this->fetch('topic/index', [
            'category' => $category,
            'paginate' => TopicModel::minePaginate($param),
        ]);
    }
}
