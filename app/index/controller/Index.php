<?php
declare (strict_types = 1);

namespace app\index\controller;

use tpadmin\model\Config as ConfigModel;

class Index
{
    public function index()
    {
        $config = ConfigModel::where('name', ConfigModel::NAME_SITE_SETTING)->find();
        if (empty($config) || empty($config->value)) {
            $site = [];
        } else {
            $site = json_decode($config->value, true);
        }

        return view('index', [
            'site'  => $site,
        ]);
    }
}
