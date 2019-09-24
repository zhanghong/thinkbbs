<?php

namespace app\index\controller;

use think\Request;
use app\common\model\User;
use app\common\exception\ValidateException;

class Register extends Base
{
    public function create()
    {
        return $this->fetch('create');
    }

    public function save(Request $request)
    {
        $param = $request->post();
        try{
            $user = User::register($param);
        }catch (ValidateException $e){
            $this->assign('user', $param);
            $this->assign('errors', $e->getData());
            return $this->fetch('create');
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }
        $this->success('注册成功', url('[page.root]'));
    }

    /**
     * 验证字段值是否唯一
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-09-24
     */
    public function check_unique(Request $request)
    {
        if(!$request->isAjax()){
            $this->redirect('[page.signup]');
        }

        $param = $request->post();
        $is_valid = User::checkFieldUnique($param);
        if($is_valid){
            echo("true");
        }else{
            echo("false");
        }
    }
}