<?php

namespace app\index\controller;

use think\Request;
use app\common\validate\User as UserValidate;

class Verify extends Base
{
    public function valid_code(Request $request)
    {
        if(!$request->isAjax()){
            $this->redirect('[page.root]');
        }else if(!$request->isPost()){
            $this->success('访问页面不存在');
        }

        $is_valid = false;

        $validate = new UserValidate();
        $param = $request->post();
        if(isset($param['sms_code'])){
            $sms_code = $param['sms_code'];
            $is_valid = $validate->checkCode($sms_code, '', $param);
        }

        if($is_valid === true){
            echo('true');
        }else{
            echo('false');
        }
    }
}