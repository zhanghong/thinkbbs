<?php

namespace app\common\validate;

use think\Validate;

class Link extends Validate
{
    protected $rule = [
        'title' => 'require|length:3,50|unique:link',
        'url' => 'require|max:100|url',
    ];

    protected $message = [
        'title.require' => '标题不能为空',
        'title.length' => '标题不能少于3个字符',
        'title.unique' => '当前标题已存在',
        'url.require' => '资源链接不能为空',
        'url.max' => '资源链接不能超过100个字符',
        'url.url' => '资源链接必须是URL',
    ];
}
