<?php
declare (strict_types = 1);

namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
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
