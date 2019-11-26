<?php
declare (strict_types = 1);

namespace app\index\controller;

use think\facade\Session;
use app\common\model\Topic as TopicModel;
use app\common\model\Category as CategoryModel;
use app\common\exception\ValidateException;

class Topic extends Base
{
    protected $middleware = [
        'auth' => ['except' => ['index']],
    ];

    public function index()
    {
        $param = $this->request->only(['order'], 'get');
        return $this->fetch('index', [
            'paginate' => TopicModel::minePaginate($param),
        ]);
    }

    public function create()
    {
        return $this->fetch('form', [
            'categories' => CategoryModel::select(),
            'topic' => [],
        ]);
    }

    public function save()
    {
        if (!$this->request->isPost() || !$this->request->isAjax()) {
            $message = '对不起，你访问页面不存在。';
            Session::flash('danger', $message);
            return $this->redirect('[topic.create]');
        }

        $message = null;
        try {
            $data = $this->request->post();
            $topic = TopicModel::createItem($data);
        } catch (ValidateException $e) {
            return $this->error($e->getMessage(), null, ['errors' => $e->getData()]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $message = '话题创建成功。';
        Session::flash('success', $message);
        return $this->success($message, '[topic.index]');
    }

    public function read($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        //
    }

    public function delete($id)
    {
        //
    }
}
