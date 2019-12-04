<?php

namespace app\common\validate;

use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'name' => 'require|length:2,4|unique:category',
        'description' => 'require|max:100',
    ];

    protected $message = [
        'name.require' => '分类名不能为空',
        'name.length' => '分类名必须在2-4个字符之间',
        'name.unique' => '分类名已存在',
        'description.require' => '分类说明不能为空',
        'description.max' => '分类说明必须在100字符以内',
    ];
}
