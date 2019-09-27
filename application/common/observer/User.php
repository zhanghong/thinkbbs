<?php

namespace app\common\observer;

use think\Db;
use app\common\model\User as UserModel;

class User
{
    public function afterDelete(UserModel $user)
    {
        // 这里不要使用 Model 删除方法，否则会出现监听事件循环调用
        // Db::name接收的参数「表名」，不用包含数据库表名辍
        Db::name('topic')->where('user_id', $user->id)->delete();
        Db::name('reply')->where('user_id', $user->id)->delete();
    }
}