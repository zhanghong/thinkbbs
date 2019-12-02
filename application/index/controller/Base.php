<?php

namespace app\index\controller;

use think\Controller;
use app\common\model\Config as ConfigModel;

class Base extends Controller
{
    protected function initialize()
    {
        if(!request()->isAjax()){
            // 读取站点设置信息
            $site = ConfigModel::siteSetting();
            $this->assign('site', $site);
        }
    }
}
