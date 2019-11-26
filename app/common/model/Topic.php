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
     * 范围查询-最近回回复排序
     * @Author   zhanghong(Laifuzi)
     * @param    Query             $query 查询构建器
     * @return   Query                    查询构建器
     */
    public static function scopeRecentReplied($query)
    {
        // 按最后更新时间排序
        return $query->order('update_time', 'DESC');
    }

    /**
     * 范围查询-最新发表
     * @Author   zhanghong(Laifuzi)
     * @param    Query             $query 查询构建器
     * @return   Query                    查询构建器
     */
    public static function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->order('id', 'DESC');
    }

    /**
     * 范围查询-排序方式
     * @Author   zhanghong(Laifuzi)
     * @param    Query              $query      查询构建器
     * @param    string             $order_type 排序方式
     * @return   Query                          查询构建器
     */
    public static function scopeWithOrder($query, $order_type)
    {
        switch ($order_type) {
            case 'recent':
                $query->recent();
                break;
            default:
                // 默认按最后回复降序排列
                $query->recentReplied();
                break;
        }

        return $query->with(['user', 'category']);
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
        $order_type = NULL;
        if (isset($param['order'])) {
            $order_type = $param['order'];
        }
        $static = static::withOrder($order_type);

        foreach ($param as $name => $value) {
            if (empty($value)) {
                continue;
            }
            switch ($name) {
                case 'category_id':
                    $static = $static->where($name, intval($value));
                    break;
            }
        }

        return $static->paginate($per_page);
    }
}
