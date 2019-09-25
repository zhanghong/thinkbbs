<?php

use think\migration\Migrator;
use think\migration\db\Column;

use app\common\model\Category;

class CreateTableCategory extends Migrator
{
    public function up()
    {
        $table = $this->table('category',array('engine'=>'InnoDB'));
        $table->addColumn('name', 'string', array('limit' => 50, 'default' => '', 'null' => false, 'comment' => '名称'))
            ->addColumn('description', 'string', array('default' => '', 'null' => false, 'comment' => '描述'))
            ->addColumn('create_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addColumn('update_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->save();

        $current_time = time();
        $categories = [
            [
                'name'        => '分享',
                'description' => '分享创造，分享发现',
                'create_time' => $current_time,
                'update_time' => $current_time,
            ],
            [
                'name'        => '教程',
                'description' => '开发技巧、推荐扩展包等',
                'create_time' => $current_time,
                'update_time' => $current_time,
            ],
            [
                'name'        => '问答',
                'description' => '请保持友善，互帮互助',
                'create_time' => $current_time,
                'update_time' => $current_time,
            ],
            [
                'name'        => '公告',
                'description' => '站点公告',
                'create_time' => $current_time,
                'update_time' => $current_time,
            ],
        ];

        Category::insertAll($categories);
    }

    public function down()
    {
        $this->dropTable('category');
    }
}