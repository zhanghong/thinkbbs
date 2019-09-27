<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

use app\common\model\User;

class ActiveUser extends Command
{
    protected function configure()
    {
        // 指令配置信息
        $this->setName('bbs:active-user')
            ->setDescription('计算活跃用户');
    }

    protected function execute(Input $input, Output $output)
    {
        User::calculateAndCacheActiveUsers();
        // 指令输出
        $output->writeln('计算活跃用户结束');
    }
}
