<?php

namespace app\common\model;

use think\Model;
use app\common\validate\Reply as Validate;
use app\common\exception\ValidateException;

class Reply extends Model
{
    protected static function init()
    {
        self::observe(\app\common\observer\Reply::class);
    }

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
                case 'keyword':
                    if(!empty($text)){
                        $like_text = '%'.$text.'%';
                        $static = $static->whereLike('content', $like_text);
                    }
                    break;
            }
        }
        return $static->with('user,topic')->paginate($page_rows, false, ['query' => $params]);
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

    /**
     * 创建回复
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-25
     * @param    array              $data 表单提交数据
     * @return   Reply                    [description]
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
            $reply = new self;
            $reply->allowField(true)->save($data);
        }catch (\Exception $e){
            throw new \Exception('创建回复失败');
        }

        return $reply;
    }

    /**
     * 是否可以删除回复
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-25
     * @return   boolean             [description]
     */
    public function canDelete()
    {
        $current_user = User::currentUser();
        if(empty($current_user)){
            return false;
        }

        if($current_user->isAuthorOf($this)){
            return true;
        }

        $topic = $this->topic;
        if(empty($topic)){
            return false;
        }else if($current_user->isAuthorOf($topic)){
            return true;
        }

        return false;
    }
}