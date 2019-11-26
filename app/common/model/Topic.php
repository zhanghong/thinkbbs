<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;
use think\Paginator;

/**
 * @mixin think\Model
 */
class Topic extends Model
{
    // belongs to user
    public function user()
    {
        return $this->belongsTo('User');
    }

    // belongs to category
    public function category()
    {
        return $this->belongsTo('Category');
    }

    /**
     * 分页查询方法
     * @Author   zhanghong(Laifuzi)
     * @param    array              $params    请求参数
     * @param    int                $page_rows 每页显示数量
     * @return   Paginator
     */
    public static function minePaginate(array $param = [], int $per_page = 20): Paginator
    {
        return static::with(['user', 'category'])->paginate($per_page);
    }
}
