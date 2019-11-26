<?php
declare (strict_types = 1);

namespace app\index\controller;

use app\common\model\Topic as TopicModel;

class Topic extends Base
{
    public function index()
    {
        $param = $this->request->only(['order'], 'get');
        return $this->fetch('index', [
            'paginate' => TopicModel::minePaginate($param),
        ]);
    }

    public function create()
    {
        //
    }

    public function save()
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

    public function update($id)
    {
        //
    }

    public function delete($id)
    {
        //
    }
}
