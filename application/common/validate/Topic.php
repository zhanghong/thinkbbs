<?php

namespace app\common\validate;

use think\Validate;

class Topic extends Validate
{
    protected $rule = [
        'title' => 'require|length:3,50',
        'category_id' => 'require|egt:1',
        'body' => 'require|min:3',
    ];

    protected $message = [
        'title.require' => '标题不能为空',
        'title.length' => '标题长度必须在3-50个字符之间',
        'category_id.require' => '分类不能为空',
        'category_id.egt' => '分类不能为空',
        'body.require' => '正文不能为空',
        'body.min' => '正文至少包含3个字符',
    ];
}