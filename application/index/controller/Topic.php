<?php

namespace app\index\controller;

use think\Request;
use app\common\model\Topic as TopicModel;
use app\common\model\Category as CategoryModel;
use app\common\exception\ValidateException;

class Topic extends Base
{
    protected $middleware = [
        'auth' => ['except' => ['index', 'read']],
    ];

    public function index(Request $request)
    {
        $param = $request->only(['order'], 'get');
        $paginate = TopicModel::minePaginate($param);
        $this->assign('paginate', $paginate);

        return $this->fetch('index');
    }

    public function create()
    {
        $categories = CategoryModel::all();
        $this->assign('categories', $categories);
        $this->assign('topic', []);
        return $this->fetch('form');
    }

    public function save(Request $request)
    {
        if(!$request->isAjax()){
            $this->redirect('[topic.create]');
        }

        try{
            $data = $request->post();
            $topic = TopicModel::createItem($data);
        }catch (ValidateException $e){
            $this->error($e->getMessage(), '', ['errors' => $e->getData()]);
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }

        $this->success('创建成功', url('[topic.read]', ['id' => $topic->id]));
    }

    public function read($id)
    {
        // 关联创建话题用户信息查询
        $topic = TopicModel::with(['user' => function($query){
            $query->field('id, name, avatar');
        }])->find($id);

        if(empty($topic)){
            $this->redirect('[topic.index]');
        }

        // 浏览次数加 1
        $topic->view_count += 1;
        $topic->save();

        $this->assign('topic', $topic);

        // 用话题的摘要信息覆盖已有的SEO信息
        $this->site['description'] = $topic->excerpt;
        $this->assign('site', $this->site);

        return $this->fetch('read');
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function delete($id)
    {
        //
    }
}
