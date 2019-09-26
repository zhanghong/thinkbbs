<?php

namespace app\common\observer;

use app\common\model\User as UserModel;
use app\common\model\Reply as ReplyModel;

class Reply
{
    public function beforeInsert(ReplyModel $reply)
    {
        if(!isset($reply['is_seeder'])){
            // 非命令行模拟时自动把当前登录用户 ID 赋值给 user_id
            $current_user = UserModel::currentUser();
            if(empty($current_user)){
                $reply->user_id = 0;
            }else{
                $reply->user_id = $current_user->id;
            }
        }
    }

    public function afterInsert(ReplyModel $reply)
    {
        $topic = $reply->topic;
        if(!empty($topic)){
            $topic->reply_count = $topic->replies()->count();
            $topic->last_reply_user_id = $reply->user_id;
            $topic->save();
        }
    }
}