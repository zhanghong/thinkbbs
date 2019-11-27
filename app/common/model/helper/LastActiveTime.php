<?php

namespace app\common\model\helper;

use think\facade\Cache;
use app\common\model\Time;

trait LastActiveTime
{
    /**
     * 同步用户昨天的活跃时间到数据库
     * @Author   zhanghong(Laifuzi)
     * @return   bool
     */
    public static function syncUserLastActiveTime(): bool
    {
        // 获取昨天的开始、结束时间戳
        list($begin_time, $end_time) = Time::yesterday();
        // 日期的缓存键名, 例：last_active_at_2019_06_28
        $date_key = static::getHashFormTime($begin_time);

        // 缓存的用户列表，读取并清除redis缓存记录
        $active_users = Cache::store('redis')->pull($date_key);
        if (empty($active_users)) {
            return true;
        }

        // 遍历并写入数据库
        foreach ($active_users as $user_id => $active_time) {
            // 会将 `user_1` 转换为 1
            $user_id = str_replace(static::LAST_ACTIVE_PREFIX, '', $user_id);
            $user = static::find($user_id);
            if (!empty($user)) {
                $user->last_active_time = $active_time;
                $user->save();
            }
        }

        return true;
    }

    /**
     * 日期的缓存键名
     * @Author   zhanghong(Laifuzi)
     * @param    int                $time 时间戳
     * @return   string
     */
    private static function getHashFormTime($time): string
    {
        $date_str = date('Y_m_d', $time);
        return static::LAST_ACTIVE_CACHE_KEY.$date_str;
    }

    /**
     * 把用户最后活跃时间写入redis缓存
     * @Author   zhanghong(Laifuzi)
     * @return   bool
     */
    public function recordLastActiveTime(): bool
    {
        // 当前时间戳
        $current_time = time();
        // 日期的缓存键名, 例：last_active_at_2019_06_28
        $date_key = static::getHashFormTime($current_time);
        // 已缓存的最后活跃用户列表
        $active_users = Cache::store('redis')->get($date_key);
        if (empty($active_users)) {
            $active_users = [];
        }

        // 把当前用户添加到最后活跃用户列表
        $user_key = $this->last_active_key;
        $active_users[$user_key] = $current_time;
        // 写入Redis缓存
        Cache::store('redis')->set($date_key, $active_users);
        return true;
    }

    /**
     * 用户在活跃用户日期缓存中的键名
     * @Author   zhanghong(Laifuzi)
     * @return   string
     */
    public function getLastActiveKeyAttr(): string
    {
        return static::LAST_ACTIVE_PREFIX.$this->id;
    }

    /**
     * 用户最后活跃时间
     * @Author   zhanghong(Laifuzi)
     * @return   string
     */
    public function getLastActiveAtAttr(): string
    {
        $current_time = time();
        $date_key = $this->getHashFormTime($current_time);
        $user_key = $this->last_active_key;
        $active_users = Cache::store('redis')->get($date_key);

        if (isset($active_users[$user_key])) {
            // 用户在今天的活跃用户列表里
            $active_time = $active_users[$user_key];
        } else if ($this->last_active_time) {
            // 用户最后活跃时间是今天以前的时间
            $active_time = $this->last_active_time;
        } else {
            // 使用用户注册时间
            $active_time = $this->getData('create_time');
        }

        return date('Y-m-d H:i:s', $active_time);
    }
}
