<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\common\model\User;

class SyncLastActive extends Command
{
    protected function configure()
    {
        $this->setName('bbs:sync-last-active')
            ->setDescription('同步用户最后活跃时间');

    }

    protected function execute(Input $input, Output $output)
    {
        User::syncUserLastActiveTime();
        $output->writeln('同步用户最后活跃时间结束');
    }
}
