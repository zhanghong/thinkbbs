<?php
declare (strict_types = 1);

namespace app\index\controller;

use app\common\model\Link as LinkModel;
use app\common\model\User as UserModel;
use app\common\model\Topic as TopicModel;

class Index extends Base
{
    public function index()
    {
        // 这里不能写成 redirect('[topic.index]')
        return $this->redirect('/topic.html');

        // 把视图目录重新设置成view
        $view_config = [
            'view_dir_name' => 'view',
        ];
        $this->app->config->set($view_config, 'view');

        $param = $this->request->only(['order'], 'get');
        return $this->fetch('topic/index', [
            'paginate' => TopicModel::minePaginate($param),
            'active_users' => UserModel::getActiveUsers(),
            'links' => LinkModel::selectAll(), // 资源推荐
        ]);
    }
}
