<?php

namespace app\index\controller;

use think\Request;
use app\common\model\Upload as UploadModel;
use app\common\exception\ValidateException;

class Upload extends Base
{
    public function create(Request $request)
    {
        return $this->save_image($request);
    }

    public function save(Request $request)
    {
        return $this->save_image($request);
    }

    private function save_image($request)
    {
        // 绑定控制名称
        $backcall = $request->param('backcall');
        // 图片预览宽度(px)
        $width = $request->param('width');
        // 图片预览高度(px)
        $height = $request->param('height');
        // 当前图片路径
        $image = $request->param('image');
        // 错误信息
        $error_msg = '';

        if($request->isPost()){
            $file = $request->file('image');
            try {
                $upload_info = UploadModel::saveImage($file);
                // 保存成功的图片路径
                $image = $upload_info['save_path'];
            } catch (ValidateException $e) {
                $errors = $e->getData();
                // 获取异常错误提示信息
                $error_msg = $errors['file'];
            }
        }

        return $this->fetch('create', [
            'backcall' => $backcall,
            'width' => $width,
            'height' => $height,
            'image' => $image,
            'error_msg' => $error_msg,
        ]);
    }
}
