<?php

namespace app\common\observer;

use think\Db;
use think\helper\Str;
use app\common\model\Topic as TopicModel;
use app\common\model\User as UserModel;

class Topic
{
    public function beforeInsert(TopicModel $topic)
    {
        if(!isset($topic['is_seeder'])){
            // 非命令行模拟时自动把当前登录用户 ID 赋值给 user_id
            $current_user = UserModel::currentUser();
            if(empty($current_user)){
                $topic->user_id = 0;
            }else{
                $topic->user_id = $current_user->id;
            }
        }
    }

    public function beforeWrite(TopicModel $topic)
    {
        $topic->excerpt = $this->makeExcerpt($topic->body);
    }

    /**
     * 生成话题摘要
     * @Author   zhanghong(Laifuzi)
     * @param    string             $value  话题正文
     * @param    integer            $length 摘要长度
     * @return   string                     生成摘要文本
     */
    protected function makeExcerpt($value, $length = 200)
    {
        $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
        return Str::substr($value, 0, $length);
    }

    public function beforeDelete(TopicModel $topic)
    {
        // 这里不要使用 Model 删除方法，否则会出现监听事件循环调用
        // Db::name接收的参数「表名」，不用包含数据库表名辍
        Db::name('reply')->where('topic_id', $topic->id)->delete();
    }
}
