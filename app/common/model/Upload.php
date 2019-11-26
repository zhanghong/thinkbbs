<?php
declare (strict_types = 1);

namespace app\common\model;

use think\facade\Filesystem;

class Upload
{
    /**
    * 保存上传图片
    * @Author   zhanghong(Laifuzi)
    * @param    File               $file         文件信息
    * @return   array
    */
    static public function saveImage($file): array
    {
        // 所有上传文件都保存在项目 public/storage/uploads 目录里
        $save_name = Filesystem::disk('public')->putFile('uploads', $file, 'md5');

        return [
            'ext' => $file->extension(),
            // 文件实际存储在 public/storage 目录里
            'save_path' => '/storage/'.$save_name,
            'sha1' => $file->hash("sha1"),
            'md5' => $file->hash("md5"),
            'size' => $file->getSize(),
            'origin_name' => $file->getOriginalName(),
        ];
    }
}
