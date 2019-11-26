<?php
declare (strict_types = 1);

namespace app\common\validate;

use think\Validate;
use think\File as ThinkFile;

class Avatar extends Validate
{
    protected $rule = [
        'file' => 'file|fileMime:image/jpeg,image/png|minWidthAndHeight:416,416',
    ];

    protected $message = [
        'file.file' => '头像必须是上传文件',
        'file.fileMime' => '头像必须是jpeg或png格式的图片',
        'file.minWidthAndHeight' => '头像的清晰度不够，宽和高需要416px以上',
    ];

    /**
     * 验证上传头像最小宽高
     * @Author   zhanghong(Laifuzi)
     * @param    string             $value 字段值
     * @param    string             $rule  字段值验证值
     * @return   string/true               验证结果
     */
    public function minWidthAndHeight($file, $rule)
    {
        if (!($file instanceof ThinkFile)) {
            return false;
        }
        if (empty($rule)) {
            return false;
        }
        $rule = explode(',', $rule);
        $min_width = intval($rule[0]);
        if (isset($rule[1])) {
            $min_height = intval($rule[1]);
        } else {
            // 不限制图片高度
            $min_height = 0;
        }
        // 获取上传文件的宽高
        list($width, $height) = getimagesize($file->getRealPath());
        if ($width < $min_width || $height < $min_height) {
            return false;
        }

        return true;
    }
}
