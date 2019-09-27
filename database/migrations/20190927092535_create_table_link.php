<?php

use think\migration\Migrator;
use think\migration\db\Column;
use app\common\model\Link;

class CreateTableLink extends Migrator
{
    public function up()
    {
        $table = $this->table('link',array('engine'=>'InnoDB'));
        $table->addColumn('title', 'string', array('limit' => 50, 'default' => '', 'null' => false, 'comment' => '标题'))
            ->addColumn('url', 'string', array('default' => '', 'null' => true, 'comment' => '链接'))
            ->addColumn('create_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addColumn('update_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->save();

        $current_time = time();
        $links = [
            [
                'title'        => 'ThinkPHP官网',
                'url' => 'http://www.thinkphp.cn/',
                'create_time' => $current_time,
                'update_time' => $current_time,
            ], [
                'title'        => '看云官网',
                'url' => 'https://www.kancloud.cn/explore',
                'create_time' => $current_time,
                'update_time' => $current_time,
            ], [
                'title'        => 'ThinkPHP 技术论坛',
                'url' => 'https://learnku.com/thinkphp',
                'create_time' => $current_time,
                'update_time' => $current_time,
            ],
        ];

        Link::insertAll($links);
    }

    public function down()
    {
        $this->dropTable('link');
    }
}
