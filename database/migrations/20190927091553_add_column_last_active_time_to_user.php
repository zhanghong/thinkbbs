<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AddColumnLastActiveTimeToUser extends Migrator
{
    public function up()
    {
        $table = $this->table('user');
        $table->addColumn('last_active_time', 'integer', array('default' => 0, 'signed' => false, 'null' => false, 'comment' => '最后活跃时间'))
            ->save();
    }

    public function down()
    {
        $table = $this->table('user');
        $table->removeColumn('last_active_time')
            ->save();
    }
}
