<?php

namespace app\common\model;

use think\Model;

class Reply extends Model
{
    // belongs to user
    public function user()
    {
        return $this->belongsTo('User');
    }

    // belongs to category
    public function topic()
    {
        return $this->belongsTo('Topic');
    }

    /**
     * 自定义查询方法
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-02-26
     * @param    array              $param    请求参数
     * @param    integer            $per_page 每页显示记录数量
     * @return   Pagination                   分页查询结果
     */
    static public function minePaginate($param, $per_page = 10)
    {

        $static = static::order('id', 'DESC');

        foreach ($param as $name => $value) {
            switch ($name) {
                case 'topic_id':
                    $static = $static->with('user')->where($name, intval($value));
                    break;
                case 'user_id':
                    $static = $static->with('topic')->where($name, intval($value));
                    break;
            }
        }
        return $static->paginate($per_page);
    }
}