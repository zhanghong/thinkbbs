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
            'https://en.gravatar.com/userimage/122970844/8db0bf1aa3aedda17973e9f3f0c011d9?size=512',
            'https://en.gravatar.com/userimage/122970844/a1b50635a6adf134ebd6ba29cc6254ad?size=512',
            'https://en.gravatar.com/userimage/122970844/342cdb104cb6d6f916788a3b18441e1a?size=512',
            'https://en.gravatar.com/userimage/122970844/92b613838e74d39bdeeacffd1f191f89?size=512',
            'https://en.gravatar.com/userimage/122970844/fdcc50b9772ee6ef52d79e3f35a149cf?size=512',
            'https://en.gravatar.com/userimage/122970844/a7843ea8ccc6fea48e28623bad816cc4?size=512',
            'https://en.gravatar.com/userimage/122970844/aabff1972e2ab8cabcf27fe6a229f681?size=512',
            'https://en.gravatar.com/userimage/122970844/ffa45dd8f9cc589d9bcf808c076f2d4a?size=512',
        ];

        $scene = 'seed_register';
        // 指定语言版本
        $faker = Factory::create('zh_CN');
        $i = 0;
        // 成功模拟10个注册用户数据后退出循环
        while($i < 10){
            $data = [];
            if($i == 0){
                // 为了方便后继章节内容的测试，这是我们默认使用的测试账号
                $data['name'] = 'laifuzi';
                $data['mobile'] = '13012341234';
            }else{
                // 随机生成一个用户昵称和手机号码
                $data['name'] = $faker->userName;
                $data['mobile'] = $faker->numberBetween(13300000000, 19000000000);
            }
            // 所有用户使用相同的登录密码
            $data['password'] = '123456';
            $data['avatar'] = $faker->randomElement($avatars);
            $data['introduction'] = $faker->sentence();

            try{
                User::register($data, $scene);
                $i++;
            }catch (\ValidateException $e){
                //nothing
            }catch (\Exception $e){
                //nothing
            }
        }
    }
}