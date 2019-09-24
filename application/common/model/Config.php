<?php

namespace app\common\model;

use think\Model;
use tpadmin\model\Config as TpadminConfig;

class Config extends TpadminConfig
{
    /**
     * 读取站点设置信息
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-10
     * @return   array             [description]
     */
    public static function siteSetting()
    {
        $config = self::where('name', self::NAME_SITE_SETTING)->find();
        if(empty($config)){
            return [];
        }

        return $config->settings;
    }
}
