<?php

namespace app\common\model;

use think\Image;
use app\common\validate\Avatar as AvatarValidate;
use app\common\exception\ValidateException;

class Upload
{
    // 上传图片类型
    // 用户头像
    public const TYPE_AVATAR = 'avatar';
    // 话题正文
    public const TYPE_CONTENT = 'content';

    // 上传图片压缩宽度
    // 用户头像
    public const MAX_WIDTH_AVATAR = 416;
    // 话题正文
    public const MAX_WIDTH_CONTENT = 1024;

   /**
    * 保存上传图片
    * @Author   zhanghong(Laifuzi)
    * @DateTime 2019-02-21
    * @param    File               $file         文件信息
    * @param    string             $type         文件类型
    * @param    string             $max_width    最大宽度
    * @return   array
    */
    static public function saveImage($file, $type, $max_width = 0){
        if($type == static::TYPE_AVATAR){
            $validate = new AvatarValidate;
            if(!$validate->batch(true)->check(['file' => $file])){
                $e = new ValidateException('上传图片失败');
                $e->setData($validate->getError());
                throw $e;
            }
        }

        // 所有上传文件都保存在项目 public/upload 目录里
        $local_dir = 'uploads';
        $ds = DIRECTORY_SEPARATOR;
        $info = $file->rule('md5')->move($local_dir);
        $save_name = $info->getSaveName();
        $save_path = $ds.$local_dir.$ds.$save_name;

        if($max_width > 0){
            //对图片进行等比缩小裁剪，并直接覆盖原图
            $image = Image::open('.'.$save_path);
            $image->thumb($max_width, $max_width)->save('.'.$save_path);
        }

        return [
            'ext' => $info->getExtension(),
            'save_path' => $save_path,
            'sha1' => $info->hash("sha1"),
            'md5' => $info->hash("md5"),
            'size' => $info->getSize(),
            'origin_name' => $file->getInfo('name'),
        ];
    }
}