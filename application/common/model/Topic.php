<?php

namespace app\common\model;

use think\Model;
use app\common\validate\Topic as Validate;
use app\common\observer\Topic as Observer;

class Topic extends Model
{
    // 注册事件观察者
    protected static function init()
    {
        self::observe(Observer::class);
    }

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

    // has many replies
    public function replies()
    {
        return $this->hasMany('Reply');
    }

    /**
     * 后台模块搜索方法
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-25
     * @param    array              $params    [description]
     * @param    integer            $page_rows [description]
     * @return   [type]                        [description]
     */
    public static function adminPaginate($params = [], $page_rows = 15)
    {
        $static = static::order('id', 'DESC');
        $map = [];
        foreach ($params as $name => $text) {
            $text = trim($text);
            switch ($name) {
                case 'category_id':
                    $static = $static->where('category_id', intval($text));
                    break;
                case 'keyword':
                    if(!empty($text)){
                        $like_text = '%'.$text.'%';
                        $static = $static->whereLike('title', $like_text);
                    }
                    break;
            }
        }
        // 同时预加载user和category进行分页查询
        return $static->with('user,category')->paginate($page_rows, false, ['query' => $params]);
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
            $topic->allowField(['title', 'category_id', 'body', 'user_id', 'excerpt'])->save($data);
        }catch (\Exception $e){
            throw new \Exception('创建话题失败');
        }

        return $topic;
    }

    /**
     * 是否可以编辑记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-21
     * @return   boolean            [description]
     */
    public function canUpdate(){
        $current_user = User::currentUser();
        if(empty($current_user)){
            return false;
        }else if(!$current_user->isAuthorOf($this)){
            return false;
        }
        return true;
    }

    /**
     * 更新记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-21
     * @param    array              $data 表单提交数据
     * @return   boolean                  是否成功
     */
    public function updateInfo($data)
    {
        $data['id'] = $this->id;

        $validate = new Validate;
        if(!$validate->batch(true)->check($data)){
            $e = new ValidateException('数据验证失败');
            $e->setData($validate->getError());
            throw $e;
        }

        $this->allowField(['title', 'category_id', 'body', 'excerpt'])->save($data, ['id' => $this->id]);
        return $this;
    }

    /**
     * 登录用户是否可以删除记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-21
     * @return   [type]             [description]
     */
    public function canDelete(){
        $current_user = User::currentUser();
        if(empty($current_user)){
            return false;
        }else if(!$current_user->isAuthorOf($this)){
            return false;
        }
        return true;
    }
}