<?php

use Faker\Factory;
use think\migration\Seeder;
use app\common\model\User;
use app\common\model\Topic;
use app\common\model\Category;

class TopicSeed extends Seeder
{
    public function run()
    {
        $faker = Factory::create('zh_CN');
        // 查询出所有User IDs
        $user_ids = User::all()->column('id');
        // 查询出所有Category IDs
        $category_ids = Category::all()->column('id');

        $i = 0;
        while($i < 100){
            $sentence = $faker->sentence();
            // 随机取一个月以内的时间
            $update_time = $faker->dateTimeThisMonth();
            // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
            $create_time = $faker->dateTimeThisMonth($update_time);
            $data = [
                'title' => $sentence,
                'excerpt' => $sentence,
                'body' => $faker->text(),
                'user_id' => $faker->randomElement($user_ids),
                'category_id' => $faker->randomElement($category_ids),
                'create_time' => $create_time->getTimestamp(),
                'update_time' => $update_time->getTimestamp(),
            ];

            $topic = new Topic($data);
            $topic->save();
            $i++;
        }

    }
}