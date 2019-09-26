<?php

use Faker\Factory;
use think\migration\Seeder;
use app\common\model\User;
use app\common\model\Topic;
use app\common\model\Reply;

class ReplySeed extends Seeder
{
    public function run()
    {
        $faker = Factory::create('zh_CN');
        // 查询出所有User IDs
        $user_ids = User::all()->column('id');
        // 查询出所有Reply IDs
        $topic_ids = Topic::all()->column('id');

        $i = 0;
        while($i < 1000){
            // 随机取一个月以内的时间
            $update_time = $faker->dateTimeThisMonth();
            // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
            $create_time = $faker->dateTimeThisMonth($update_time);
            $data = [
                'content' => $faker->text(),
                'user_id' => $faker->randomElement($user_ids),
                'topic_id' => $faker->randomElement($topic_ids),
                'create_time' => $create_time->getTimestamp(),
                'update_time' => $update_time->getTimestamp(),
            ];

            $reply = new Reply($data);
            // is_seeder=true表示是命令行模拟数据，观察者忽略对user_id属性赋值
            $reply->is_seeder = true;
            $reply->save();
            $i++;
        }

    }
}
