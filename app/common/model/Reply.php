<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;
use think\Paginator;

/**
 * @mixin think\Model
 */
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
     * @param    array              $param    请求参数
     * @param    int                $per_page 每页显示记录数量
     * @return   Paginator                    分页查询结果
     */
    static public function minePaginate(array $param, int $per_page = 10): Paginator
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
