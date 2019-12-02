<?php

namespace app\common\model;

use think\Model;
use tpadmin\model\Config as TpadminConfig;

class Config extends TpadminConfig
{
    /**
     * 读取站点设置信息
     * @Author   zhanghong(Laifuzi)
     * @return   array             [description]
     */
    public static function siteSetting()
    {
        $config = static::where('name', static::NAME_SITE_SETTING)->find();
        if(empty($config)){
            return [];
        }

        return $config->settings;
    }
}
