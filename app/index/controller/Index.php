<?php
declare (strict_types = 1);

namespace app\index\controller;

class Index extends Base
{
    public function index()
    {
        return $this->redirect('/');
    }
}
