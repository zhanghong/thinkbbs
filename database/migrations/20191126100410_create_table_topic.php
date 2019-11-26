<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateTableTopic extends Migrator
{
    public function up()
    {
        $table = $this->table('topic',array('engine'=>'InnoDB'));
        $table->addColumn('title', 'string', array('default' => '', 'null' => false, 'comment' => '话题标题'))
            ->addColumn('body', 'text', array('comment' => '话题内容'))
            ->addColumn('user_id', 'integer', array('default' => 0, 'signed' => false, 'null' => false, 'comment' => '创建用户ID'))
            ->addColumn('category_id', 'integer', array('default' => 0, 'signed' => false, 'null' => false, 'comment' => '分类ID'))
            ->addColumn('reply_count', 'integer', array('default' => 0, 'signed' => false, 'null' => false, 'comment' => '回复数量'))
            ->addColumn('view_count', 'integer', array('default' => 0, 'signed' => false, 'null' => false, 'comment' => '查看总数'))
            ->addColumn('last_reply_user_id', 'integer', array('default' => 0, 'signed' => false, 'null' => false, 'comment' => '最后回复的用户ID'))
            ->addColumn('excerpt', 'string', array('default' => '', 'limit' => 255, 'null' => false, 'comment' => '文章摘要，SEO优化时使用'))
            ->addColumn('create_time', 'integer', array('default' => 0, 'signed' => false, 'null' => false))
            ->addColumn('update_time', 'integer', array('default' => 0, 'signed' => false, 'null' => false))
            ->addIndex(['title'])
            ->addIndex(['user_id'])
            ->addIndex(['category_id'])
            ->save();
    }

    public function down()
    {
        $this->dropTable('topic');
    }
}
