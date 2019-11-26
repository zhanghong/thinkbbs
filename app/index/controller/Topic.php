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
        'auth' => ['except' => ['index', 'read']],
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
        return $this->success($message, url('[topic.read]', ['id' => $topic->id]));
    }

    public function read($id)
    {
        // 关联创建话题用户信息查询
        $topic = TopicModel::with(['user' => function($query) {
            $query->field('id, name, avatar');
        }])->find($id);

        if (empty($topic)) {
            return $this->redirect('[topic.index]');
        }

        // 浏览次数加 1
        $topic->view_count += 1;
        $topic->save();

        // 用话题的摘要信息覆盖已有的SEO信息
        $this->site['description'] = $topic->excerpt;

        return $this->fetch('topic/read', [
          'topic' => $topic,
          'site' => $this->site,
        ]);
    }

    public function edit($id)
    {
        $message = null;
        $topic = TopicModel::find($id);

        if (empty($topic)) {
            $message = '对不起，编辑话题不存在。';
        } else if (!$topic->canUpdate()) {
            $message = '对不起，您没有权限编辑该话题。';
        }

        if (!empty($message)) {
            Session::set('danger', $message);
            return $this->redirect('[topic.index]');
        }

        return $this->fetch('topic/form', [
            'topic' => $topic,
            'categories' => CategoryModel::select(),
        ]);
    }

    public function update($id)
    {
        $message = null;
        $topic = TopicModel::find($id);

        if (empty($topic)) {
            $message = '对不起，编辑话题不存在。';
        } else if (!$topic->canUpdate()) {
            $message = '对不起，您没有权限编辑该话题。';
        } else if (!$this->request->isPut() || !$this->request->isAjax()) {
            $message = '对不起，您访问的页面不存在。';
        }

        if (!empty($message)) {
            Session::set('danger', $message);
            return $this->error($message, '[topic.index]');
        }

        try {
            $data = $this->request->post();
            $topic->updateInfo($data);
        } catch (ValidateException $e) {
            return $this->error($e->getMessage(), null, ['errors' => $e->getData()]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $message = '话题更新成功。';
        Session::set('success', $message);
        return $this->success($message, url('[topic.read]', ['id' => $topic->id]));
    }

    public function delete($id)
    {
        $message = null;
        $topic = TopicModel::find($id);

        if (empty($topic)) {
            return $this->error('删除话题不存在', '[topic.index]');
        } else if (!$topic->canDelete()) {
            return $this->error('对不起，您没有权限删除该话题', '[topic.index]');
        }

        $topic->delete();

        $message = '删除成功';
        Session::set('success', $message);
        return $this->success($message, '[topic.index]');
    }
}
