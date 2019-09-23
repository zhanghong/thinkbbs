<?php

use think\migration\Migrator;
use think\migration\db\Column;

class TpAdminInit extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $table = $this->table('adminer',array('engine'=>'InnoDB'));
        $table->addColumn('name', 'string', array('limit' => 50, 'default' => '', 'null' => false))
            ->addColumn('password', 'string', array('default' => '', 'null' => false))
            ->addColumn('status', 'boolean', array('default' => 1, 'null' => true))
            ->addColumn('login_ip', 'string', array('limit' => 50, 'default' => '', 'null' => true))
            ->addColumn('login_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addColumn('is_default', 'boolean', array('default' => 0, 'null' => true))
            ->addColumn('create_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addColumn('update_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addIndex(['name'], ['unique' => true])
            ->save();

        $table = $this->table('auth_rule',array('engine'=>'InnoDB'));
        $table->addColumn('name', 'string', array('limit' => 80, 'default' => '', 'null' => true))
            ->addColumn('title', 'string', array('limit' => 20, 'default' => '', 'null' => false))
            ->addColumn('type', 'boolean', array('default' => true, 'null' => true))
            ->addColumn('parent_id', 'integer', array('default' => 0, 'signed' => false, 'null' => false))
            ->addColumn('sort_num', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addColumn('route_name', 'string', array('limit' => 100, 'default' => '', 'null' => true))
            ->addColumn('icon', 'string', array('limit' => 50, 'default' => '', 'null' => true))
            ->addColumn('status', 'boolean', array('default' => 1, 'null' => false))
            ->addColumn('condition', 'string', array('limit' => 100, 'default' => '', 'null' => true))
            ->addColumn('tips', 'text', array('null' => true))
            ->addColumn('create_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addColumn('update_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addIndex(['name'], ['name' => 'idx_name', 'unique' => true])
            ->save();

        $table = $this->table('auth_role',array('engine'=>'InnoDB'));
        $table->addColumn('title', 'string', array('limit' => 20, 'default' => '', 'null' => false))
            ->addColumn('status', 'boolean', array('default' => 1, 'null' => false))
            ->addColumn('create_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addColumn('update_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->save();

        $table = $this->table('auth_role_user',array('engine'=>'InnoDB'));
        $table->addColumn('user_id', 'integer', array('default' => 0, 'signed' => false, 'null' => false))
            ->addColumn('role_id', 'integer', array('default' => 0, 'signed' => false, 'null' => false))
            ->addColumn('create_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addColumn('update_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addIndex(['user_id', 'role_id'], ['name' => 'unique_user_and_role', 'unique' => true])
            ->addIndex(['user_id'], ['name' => 'idx_user_id'])
            ->addIndex(['role_id'], ['name' => 'idx_role_id'])
            ->save();

        $table = $this->table('auth_role_rule',array('engine'=>'InnoDB'));
        $table->addColumn('rule_id', 'integer', array('default' => 0, 'signed' => false, 'null' => false))
            ->addColumn('role_id', 'integer', array('default' => 0, 'signed' => false, 'null' => false))
            ->addColumn('create_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addColumn('update_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
            ->addIndex(['rule_id', 'role_id'], ['name' => 'unique_rule_and_role', 'unique' => true])
            ->addIndex(['rule_id'], ['name' => 'idx_rule_id'])
            ->addIndex(['role_id'], ['name' => 'idx_role_id'])
            ->save();

        $table = $this->table('config',array('engine'=>'InnoDB'));
        $table->addColumn('name', 'string', array('limit' => 60, 'default' => ''))
                ->addColumn('value', 'text')
                ->addColumn('create_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
                ->addColumn('update_time', 'integer', array('default' => 0, 'signed' => false, 'null' => true))
                ->save();
    }

    public function down()
    {
        $this->dropTable('adminer');
        $this->dropTable('auth_rule');
        $this->dropTable('auth_role');
        $this->dropTable('auth_role_user');
        $this->dropTable('auth_role_rule');
        $this->dropTable('config');
    }
}
