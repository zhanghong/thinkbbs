<?php
declare (strict_types = 1);

namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

use app\common\model\User;

class ActiveUser extends Command
{
    protected function configure()
    {
        // ThinkPHP6.0 在这里设置的指令名称没有意义
        // 因为在控制台运行时看的是 config/console.php 里的指令名称
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
