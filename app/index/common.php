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
