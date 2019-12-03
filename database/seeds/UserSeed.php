<?php

use Faker\Factory;
use think\migration\Seeder;
use app\common\model\User;
use app\common\exception\ValidateException;

class UserSeed extends Seeder
{
    public function run()
    {
        // 头像假数据
        $avatars = [
            'http://tbs.zhanghong.info/images/avatar/female_1.png',
            'http://tbs.zhanghong.info/images/avatar/female_2.png',
            'http://tbs.zhanghong.info/images/avatar/female_4.png',
            'http://tbs.zhanghong.info/images/avatar/female_5.png',
            'http://tbs.zhanghong.info/images/avatar/male_1.png',
            'http://tbs.zhanghong.info/images/avatar/male_3.png',
            'http://tbs.zhanghong.info/images/avatar/male_5.png',
            'http://tbs.zhanghong.info/images/avatar/male_6.png',
        ];

        $scene = 'seed_register';
        // 指定语言版本
        $faker = Factory::create('zh_CN');
        $i = 0;
        // 成功模拟10个注册用户数据后退出循环
        while ($i < 10) {
            $data = [];
            if ($i == 0) {
                // 为了方便后继章节内容的测试，这是我们默认使用的测试账号
                $data['name'] = 'laifuzi';
                $data['mobile'] = '13012341234';
            } else {
                // 随机生成一个用户昵称和手机号码
                $data['name'] = $faker->userName;
                $data['mobile'] = $faker->numberBetween(13300000000, 19000000000);
            }
            // 所有用户使用相同的登录密码
            $data['password'] = '123456';
            $data['avatar'] = $faker->randomElement($avatars);
            $data['introduction'] = $faker->sentence();

            try {
                User::register($data, $scene);
                $i++;
            } catch (\ValidateException $e) {
                //nothing
            } catch (\Exception $e) {
                //nothing
            }
        }
    }
}
