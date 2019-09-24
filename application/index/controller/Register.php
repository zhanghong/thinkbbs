<?php

namespace app\index\controller;

use think\Request;
use app\common\model\Sms;
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

    /**
     * 发送注册验证码
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-24
     */
    public function send_code(Request $request)
    {
        if(!$request->isAjax()){
            $this->redirect('[page.signup]');
        }else if(!$request->isPost()){
            $this->error('访问页面不存在');
        }

        $mobile = $request->post('mobile');
        if(empty($mobile)){
            $this->error('注册手机号码不能为空');
        }
        $param = ['name' => 'mobile', 'mobile' => $mobile];
        if(User::checkFieldUnique($param)){
            $this->error('手机号码已注册');
        }

        try {
            $sms = new Sms();
            $sms->sendCode($mobile);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        $this->success('验证码发送成功');
    }
}