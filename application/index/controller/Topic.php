<?php

namespace app\index\controller;

use think\Request;
use app\common\model\Topic as TopicModel;

class Topic extends Base
{
    public function index()
    {
        $paginate = TopicModel::paginate(20);
        $this->assign('paginate', $paginate);

        return $this->fetch('index');
    }

    public function create()
    {
        //
    }

    public function save(Request $request)
    {
        //
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
