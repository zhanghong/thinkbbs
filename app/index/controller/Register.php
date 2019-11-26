<?php
declare (strict_types = 1);

namespace app\index\controller;

use app\common\model\User;

class Register extends Base
{
    public function create()
    {
        return $this->fetch('create');
    }

    public function save(User $user)
    {
        if (!$this->request->isPost() || !$this->request->isAjax()) {
            return $this->error('对不起，你访问页面不存在。');
        }

        try {
            // 保存表单提交数据
            $user->save($this->request->post());
        } catch (\Exception $e) {
            return $this->error('对不起，注册失败。');
        }

        // 注册成功后跳转到首页
        return $this->success('恭喜你注册成功。', '/');
    }

    /**
     * 验证字段值是否唯一
     * @Author   zhanghong(Laifuzi)
     */
    public function check_unique()
    {
        if (!$this->request->isPost() || !$this->request->isAjax()) {
            $this->redirect('[page.signup]');
        }

        $param = $this->request->post();
        $is_valid = User::checkFieldUnique($param);
        if ($is_valid) {
            echo 'true';
        } else {
            echo 'false';
        }
    }
}
