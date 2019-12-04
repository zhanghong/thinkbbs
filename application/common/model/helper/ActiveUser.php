<?php

namespace app\common\model\helper;

use think\facade\Cache;
use app\common\model\Time;
use app\common\model\Topic;
use app\common\model\Reply;

trait ActiveUser
{
    /**
     * 获取活跃用户列表
     * @Author   zhanghong(Laifuzi)
     * @return   array
     */
    public static function getActiveUsers()
    {
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出活跃用户数据，返回的同时做了缓存。
        return Cache::store('redis')->remember(static::ACTIVE_CACHE_KEY, function() {
            return static::calculateActiveUsers();
        }, static::ACTIVE_CACHE_SECONDS);
    }

    /**
     * 计算并缓存活跃用户列表
     * @Author   zhanghong(Laifuzi)
     * @return   array
     */
    public static function calculateAndCacheActiveUsers()
    {
        // 计算活跃用户列表
        $active_users = static::calculateActiveUsers();
        // 缓存计算结果
        static::cacheActiveUsers($active_users);
        return $active_users;
    }

    /**
     * 计算活跃用户列表
     * @Author   zhanghong(Laifuzi)
     * @return   array
     */
    public static function calculateActiveUsers()
    {
        $active_users = [];

        list($gte_time, $end_time) = Time::dayToNow(static::PASS_DAYS);

        // 计算用户Topic积分
        $topic_users = Topic::fieldRaw('user_id, COUNT(*) AS topic_count')
                            ->where('create_time', '>=', $gte_time)
                            ->group('user_id')
                            ->select();
        foreach ($topic_users as $item) {
            $arr_key = strval($item->user_id);
            $active_users[$arr_key] = [
                'id' => $item->user_id,
                'score' => $item->topic_count * static::TOPIC_WEIGHT,
            ];
        }

        // 计算用户Reply积分
        $reply_users = Reply::fieldRaw('user_id, COUNT(*) AS reply_count')
                            ->where('create_time', '>=', $gte_time)
                            ->group('user_id')
                            ->select();
        foreach ($reply_users as $item) {
            $reply_score = $item->reply_count * static::REPLY_WEIGHT;
            $arr_key = strval($item->user_id);
            if (isset($active_users[$arr_key])) {
                $active_users[$arr_key]['score'] += $reply_score;
            } else {
                $active_users[$arr_key] = [
                    'id' => $item->user_id,
                    'score' => $reply_score,
                ];
            }
        }

        return static::sortAndSelect($active_users);
    }

    /**
     * 对活跃用户按积分降序排列并返回用户信息
     * @Author   zhanghong(Laifuzi)
     * @param    array              $users 活跃用户积分列表
     * @return   array
     */
    private static function sortAndSelect($users)
    {
        if (empty($users)) {
            return [];
        }

        // 按积分对user_id进行降序排列
        $user_scores = array_column($users, 'score');
        $user_ids = array_column($users, 'id');
        array_multisort($user_scores, SORT_DESC, $user_ids);

        // 取出排序结果里前ACTIVE_LIMIT_NUMBER个user_id
        $top_user_ids = array_slice($user_ids, 0, static::ACTIVE_LIMIT_NUMBER);
        $ids_str = implode(',', $top_user_ids);
        $order_field_str = 'FIELD(id, '.$ids_str.')';

        // 按照top_user_ids的排列顺序查询出Top User信息
        return static::fieldRaw('id,name,avatar')
                    ->whereIn('id', $top_user_ids)
                    ->orderRaw($order_field_str)
                    ->select();
    }

    /**
     * 缓存活动用户列表
     * @Author   zhanghong(Laifuzi)
     * @param    array              $active_users 活跃用户列表
     * @return   bool
     */
    private static function cacheActiveUsers($active_users)
    {
        // 将数据放入缓存中
        Cache::store('redis')->set(static::ACTIVE_CACHE_KEY, $active_users, static::ACTIVE_CACHE_SECONDS);
    }
}
