<?php

namespace app\common\observer;

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
}
