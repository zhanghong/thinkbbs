<?php

namespace app\common\model;

use think\Model;
use app\common\validate\Topic as Validate;

class Topic extends Model
{
    // 新增实例记录时自动完成user_id字段赋值
    protected $insert = ['user_id'];

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
     * @DateTime 2019-02-25
     * @param    [type]             $query 查询构建器
     * @return   [type]                    查询构建器
     */
    public static function scopeRecentReplied($query)
    {
        // 按最后更新时间排序
        return $query->order('update_time', 'DESC');
    }

    /**
     * 范围查询-最新发表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-02-25
     * @param    [type]             $query 查询构建器
     * @return   [type]                    查询构建器
     */
    public static function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->order('id', 'DESC');
    }

    /**
     * 范围查询-排序方式
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-02-25
     * @param    [type]             $query      查询构建器
     * @param    [type]             $order_type 排序方式
     * @return   [type]                         查询构建器
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

        return $query->with('user,category');
    }

    /**
     * 分页查询方法
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-20
     * @param    array              $params    请求参数
     * @param    integer            $page_rows 每页显示数量
     * @return   [type]                        分页查询结果
     */
    public static function minePaginate($param = [], $per_page = 20)
    {
        $order_type = NULL;
        if(isset($param['order'])){
            $order_type = $param['order'];
        }
        $static = static::withOrder($order_type);

        foreach ($param as $name => $value) {
            if(empty($value)){
                continue;
            }
            switch ($name) {
                case 'user_id':
                case 'category_id':
                    $static = $static->where($name, intval($value));
                    break;
            }
        }

        return $static->paginate($per_page);
    }

    /**
     * user_id属性修改器
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-21
     */
    protected function setUserIdAttr(){
        // 当前登录用户ID
        $current_user = User::currentUser();
        if(empty($current_user)){
            return 0;
        }
        return $current_user->id;
    }

    /**
     * 创建记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-21
     * @param    array              $data 表单提交数据
     * @return   Topic                    [description]
     */
    public static function createItem($data)
    {
        $validate = new Validate;
        if(!$validate->batch(true)->check($data)){
            $e = new ValidateException('数据验证失败');
            $e->setData($validate->getError());
            throw $e;
        }

        try{
            $topic = new self;
            $topic->allowField(['title', 'category_id', 'body'])->save($data);
        }catch (\Exception $e){
            throw new \Exception('创建话题失败');
        }

        return $topic;
    }
}