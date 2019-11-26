<?php
declare (strict_types = 1);

namespace app\index\controller;

use app\common\model\Upload as UploadModel;

class Upload extends Base
{
    public function create()
    {
        return $this->save_image();
    }


    public function save()
    {
        return $this->save_image();
    }

    private function save_image()
    {
        // 绑定控制名称
        $backcall = $this->request->param('backcall');
        // 图片预览宽度(px)
        $width = $this->request->param('width', 100);
        // 图片预览高度(px)
        $height = $this->request->param('height', 100);

        if ($this->request->isPost()) {
            // 保存上传图片
            $file = $this->request->file('image');
            $upload_info = UploadModel::saveImage($file);
            // 保存成功的图片路径
            $image = $upload_info['save_path'];
        } else {
            // 当前图片路径
            $image = $this->request->param('image');
        }

        return $this->fetch('create', [
            'backcall' => $backcall,
            'width' => $width,
            'height' => $height,
            'image' => $image,
        ]);
    }
}
