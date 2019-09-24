<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateTableUser extends Migrator
{
    public function up()
    {
        $table = $this->table('user',array('engine'=>'InnoDB'));
        $table->addColumn('name', 'string', array('limit' => 50, 'default' => '', 'null' => false, 'comment' => '用户名'))
            ->addColumn('mobile', 'string', array('limit' => 11, 'default' => '', 'null' => false, 'comment' => '注册手机'))
            ->addColumn('password', 'string', array('limit' => 60, 'default' => '', 'null' => false, 'comment' => '登录密码'))
            ->addColumn('avatar', 'string', array('default' => '', 'null' => false, 'comment' => '用户头像'))
            ->addColumn('introduction', 'string', array('default' => '', 'null' => false, 'comment' => '个人介绍'))
            ->addColumn('create_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addColumn('update_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addIndex(['mobile'], ['unique' => true])
            ->save();
    }

    public function down()
    {
        $this->dropTable('user');
    }
}