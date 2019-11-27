<?php

return [
    // 指令定义
    'commands' => [
        'bbs:active-user' => 'app\common\command\ActiveUser',
        'bbs:sync-last-active' => 'app\common\command\SyncLastActive',
    ],
];
