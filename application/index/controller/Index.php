<?php

namespace app\index\controller;

use think\Controller;
use tpadmin\model\Config as ConfigModel;

class Index extends Controller
{
    public function index()
    {
        $config = ConfigModel::where('name', ConfigModel::NAME_SITE_SETTING)->find();
        if(empty($config) || empty($config->value)){
            $site = [];
        }else{
            $site = json_decode($config->value, true);
        }
        $this->assign('site', $site);
        return $this->fetch('index');
    }
}
