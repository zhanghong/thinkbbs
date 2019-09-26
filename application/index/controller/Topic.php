<?php

namespace app\index\controller;

use think\Request;
use app\common\model\Topic as TopicModel;
use app\common\model\Category as CategoryModel;
use app\common\exception\ValidateException;

class Topic extends Base
{
    protected $middleware = [
        'auth' => ['except' => ['index']],
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

        $this->success('创建成功', url('[topic.index]'));
    }

    public function read($id)
    {
        //
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
