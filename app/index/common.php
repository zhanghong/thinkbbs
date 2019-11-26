<?php
/**
 * 路由页面css class名
 * @Author   zhanghong(Laifuzi)
 * @return   string
 */
function route_class(): string
{
    $request = request();
    // 获取请求控制器名称并转化成小写格式
    $ctr_name = $request->controller(true);
    // 获取请求操作方法名称并转化成小写格式
    $act_name = $request->action(true);
    $class_name = $ctr_name . '-' .$act_name;

    return implode('-', [$ctr_name, $act_name, 'page']);
}

/**
 * 资源文件包含最后修改时间戳路径
 * @Author   zhanghong(Laifuzi)
 * @param    string             $file_path 资源文件路径
 * @return   string
 */
function asset_path(string $file_path): string
{
    try {
        // 项目根目录
        $root_path = app()->getRootPath();
        // 资源文件全路径
        $full_path = $root_path.'public/'.$file_path;
        $info = new \SplFileInfo($full_path);
        $file_time = $info->getCTime();
    } catch (\Exception $e) {
        $file_time = time();
    }
    return $file_path.'?c='.$file_time;
}

/**
 * 上传单张图片--返回的是Upload Image Path
 * @Author   zhanghong
 * @param    string     $backcall 回调字段名
 * @param    integer    $width    图片高度
 * @param    integer    $height   图片宽度
 * @param    string     $image    当前图片路径
 * @param    string     $upload_type    上传文件类型
 */
function create_upload_image($backcall="image", $width=100, $height=100, $image="")
{
    echo '<iframe scrolling="no" frameborder="0" border="0" onload="this.height=this.contentWindow.document.body.scrollHeight;this.width=this.contentWindow.document.body.scrollWidth;" width='.$width.' height="'.$height.'"  src="'.url('[upload.create]').'?width='.$width.'&height='.$height.'&backcall='.$backcall.'&image='.$image.'"></iframe>
         <input type="hidden" name="'.$backcall.'" id="'.$backcall.'">';
}

/**
 * 顶部导航是否选中样式
 * @Author   zhanghong(Laifuzi)
 * @param    string             $route_name 路由路径
 * @param    array              $param      判断参数
 * @return   string
 */
function navbar_class(string $route_name, array $param = []): string
{
    $request = request();
    // 获取请求控制器名称并转化成小写格式
    $ctr_name = $request->controller(true);
    // 获取请求操作方法名称并转化成小写格式
    $act_name = $request->action(true);
    $page_route = $ctr_name . '/' .$act_name;

    if ($route_name != $page_route) {
        // 当前路由路径 和 菜单项路由路径不相等
        return '';
    }

    if (empty($param)) {
        // 当前路由路径 和 菜单项路由路径相等 并且没有判断参数
        // 菜单项必然是选中状态
        return 'active';
    }

    $is_active = true;
    // 只有当所有 判断参数 都相等时菜单项才是选中状态
    foreach ($param as $name => $value) {
        $param_value = $request->param($name);
        $pm[$name] = $param_value;
        if ($param_value != $value) {
            $is_active = false;
            break;
        }
    }

    if ($is_active) {
        return 'active';
    } else {
        return '';
    }
}

/**
 * 排序方式导航是否active样式名
 * @Author   zhanghong(Laifuzi)
 * @param    string             $name     判断参数名
 * @param    string             $value    判断参数值
 * @param    bool               $is_equal 判断方式
 * @return   string
 */
function order_active(string $name, string $value, bool $is_equal = true): string
{
    // 获取当前请求信息里的参数值
    $param_value = request()->get($name);
    $is_active = false;
    if ($is_equal == true && $param_value == $value) {
        // 页面请求参数值和判断参数值相等时为选中样式
        $is_active = true;
    } else if ($is_equal != true && $param_value != $value) {
        // 页面请求参数值和判断参数值不等时为选中样式
        $is_active = true;
    }

    if ($is_active) {
        return 'active';
    } else {
        return '';
    }
}
