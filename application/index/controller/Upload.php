<?php

namespace app\index\controller;

use think\Request;
use app\common\model\Upload as UploadModel;

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
        $width = $request->param('width', 100);
        // 图片预览高度(px)
        $height = $request->param('height', 100);

        if ($request->isPost()) {
            // 保存上传图片
            $file = $request->file('image');
            $upload_info = UploadModel::saveImage($file);
            // 保存成功的图片路径
            $image = $upload_info['save_path'];
        } else {
            // 当前图片路径
            $image = $request->param('image');
        }

        return $this->fetch('create', [
            'backcall' => $backcall,
            'width' => $width,
            'height' => $height,
            'image' => $image,
        ]);
    }
}
