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
                // 把用户头像等比缩小裁剪成宽度 416px
                $upload_info = UploadModel::saveImage($file, UploadModel::TYPE_AVATAR, UploadModel::MAX_WIDTH_AVATAR);
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

    public function simditor(Request $request)
    {
        $file = $request->file('upfile');
        try {
            // 正文图片宽度压缩到 1024px
            $upload_info = UploadModel::saveImage($file, UploadModel::TYPE_CONTENT, UploadModel::MAX_WIDTH_CONTENT);
            $data = [
                'status' => true,
                'msg' => '上传成功',
                'file_path' => $upload_info['save_path'],
            ];
        } catch (\Expection $e) {
            $data = [
                'status' => false,
                'msg' => $e->getMessage(),
            ];
        }

        // 变量$data值已经是Simditor编辑器需要返回的格式了
        // 所以直接调用json()方法返回该数据
        return json($data);
    }
}
