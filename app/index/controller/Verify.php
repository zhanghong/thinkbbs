<?php
declare (strict_types = 1);

namespace app\index\controller;

use app\common\validate\User as UserValidate;

class Verify extends Base
{
    public function valid_code()
    {
        if (!$this->request->isAjax()) {
            return $this->redirect('/');
        } else if (!$this->request->isPost()) {
            return $this->error('对不起，你访问页面不存在。', '/');
        }

        $is_valid = false;

        $validate = new UserValidate();
        $param = $this->request->post();
        if (isset($param['sms_code'])) {
            $sms_code = $param['sms_code'];
            $is_valid = $validate->checkCode($sms_code, '', $param);
        }

        if ($is_valid === true) {
            echo('true');
        } else {
            echo('false');
        }
    }
}
