<?php

use think\Container;

/**
 * 路由页面css class名
 * @Author   zhanghong(Laifuzi)
 * @return   string
 */
function route_class()
{
    $request = request();
    try{
        $ctr_name = $request->controller(true);
        $act_name = $request->action(true);
        $class_name = $ctr_name . '-' .$act_name;
    }catch(\Exception $e){
        $route_info = $request->routeInfo();
        if(empty($route_info) || empty($route_info['route'])){
            $class_name = 'none';
        }else{
            $class_name = str_replace(['/'], '-', $route_info['route']);
        }
    }

    return  strtolower($class_name).'-page';
}

/**
 * 资源文件包含最后修改时间戳路径
 * @Author   zhanghong(Laifuzi)
 * @param    string             $file_path 资源文件路径
 * @return   string
 */
function asset_path($file_path)
{
    try{
        // 项目根目录
        $root_path = Container::get('app')->getRootPath();
        // 资源文件全路径
        $full_path = $root_path.'public/'.$file_path;
        $info = new \SplFileInfo($full_path);
        $file_time = $info->getCTime();
    }catch(\Exception $e){
        $file_time = time();
    }
    return $file_path.'?c='.$file_time;
}
