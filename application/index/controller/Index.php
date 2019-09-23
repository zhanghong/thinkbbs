<?php

namespace app\index\controller;

use think\Controller;
use tpadmin\model\Config as ConfigModel;

class Index extends Controller
{
    public function index()
    {
        $config = ConfigModel::where('name', ConfigModel::NAME_SITE_SETTING)->find();
        if(empty($config)){
            return [];
        }
        $this->assign('site', $config->settings);
        return $this->fetch('index');
    }
}