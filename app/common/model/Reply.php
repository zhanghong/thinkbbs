<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;
use think\Paginator;
use think\facade\Config;
use app\common\validate\Reply as Validate;
use app\common\exception\ValidateException;

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

    /**
     * 评论新增后事件
     * @Author   zhanghong(Laifuzi)
     * @param    Reply              $reply 评论实例
     * @return   bool
     */
    public static function onBeforeInsert(Reply $reply)
    {
        $cfg = Config::get('purifier');
        $config = \HTMLPurifier_HTML5Config::create($cfg);
        $purifier = new \HTMLPurifier($config);
        $reply->content = $purifier->purify($reply->content);

        return true;
    }

    /**
     * 创建回复
     * @Author   zhanghong(Laifuzi)
     * @param    array              $data 表单提交数据
     * @return   Reply
     */
    public static function createItem(array $data): Reply
    {
        $validate = new Validate;
        if (!$validate->batch(true)->check($data)) {
            $e = new ValidateException('数据验证失败');
            $e->setData($validate->getError());
            throw $e;
        }

        // 把当前登录用户 ID 赋值给 user_id
        $data['user_id'] = 0;
        $current_user = User::currentUser();
        if (!empty($current_user)) {
            $data['user_id'] = $current_user->id;
        }

        try {
            $reply = new self;
            $reply->allowField(['topic_id', 'user_id', 'content'])->save($data);
        } catch (\Exception $e) {
            throw new \Exception('创建回复失败');
        }

        return $reply;
    }

    /**
     * 评论新增后事件
     * @Author   zhanghong(Laifuzi)
     * @param    Reply              $reply 评论实例
     */
    public static function onAfterInsert(Reply $reply)
    {
        $topic = $reply->topic;
        if (!empty($topic)) {
            $topic->reply_count = $topic->replies()->count();
            $topic->last_reply_user_id = $reply->user_id;
            $topic->save();
        }
    }

    /**
     * 是否可以删除回复
     * @Author   zhanghong(Laifuzi)
     * @return   bool
     */
    public function canDelete(): bool
    {
        $current_user = User::currentUser();
        if (empty($current_user)) {
            return false;
        }

        if ($current_user->isAuthorOf($this)) {
            return true;
        }

        $topic = $this->topic;
        if (empty($topic) || $current_user->isAuthorOf($topic)) {
            // 回复所属话题为空时也可以被删除
            return true;
        }

        return false;
    }

    /**
     * 话题删除后事件
     * @Author   zhanghong(Laifuzi)
     * @param    Reply              $reply 评论实例
     */
    public static function onAfterDelete(Reply $reply)
    {
        $topic = $reply->topic;
        if (!empty($topic)) {
            $topic->reply_count = $topic->replies()->count();
            $topic->save();
        }
    }
}
