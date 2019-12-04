<?php

namespace app\common\model;

class Upload
{
   /**
    * 保存上传图片
    * @Author   zhanghong(Laifuzi)
    * @param    File               $file         文件信息
    * @return   array
    */
    static public function saveImage($file){
        // 所有上传文件都保存在项目 public/upload 目录里
        $local_dir = 'uploads';
        $ds = DIRECTORY_SEPARATOR;
        $info = $file->rule('md5')->move($local_dir);
        $save_name = $info->getSaveName();
        $save_path = $ds.$local_dir.$ds.$save_name;

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
