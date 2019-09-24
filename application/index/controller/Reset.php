<?php

namespace app\index\controller;

use think\Request;
use think\facade\Session;
use app\common\model\Sms;
use app\common\model\User;
use app\common\exception\ValidateException;

class Reset extends Base
{
    public function create()
    {
        return $this->fetch('create');
    }

    public function save(Request $request)
    {
        if(!$request->isAjax()){
            $this->redirect('[page.reset]');
        }else if(!$request->isPost()){
            $this->error('访问页面不存在');
        }

        $param = $request->post();
        try{
            User::resetPassword($param);
        }catch (ValidateException $e){
            $this->error('验证失败', '', ['errors' => $e->getData()]);
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }

        $message = '重置密码成功';
        Session::set('success', $message);
        $this->success($message, url('[page.login]'));
    }

    public function send_code(Request $request)
    {
        if(!$request->isAjax()){
            $this->redirect('[page.reset]');
        }else if(!$request->isPost()){
            $this->error('访问页面不存在');
        }

        $mobile = $request->post('mobile');
        if(empty($mobile)){
            $this->error('注册手机号码不能为空');
        }else if(!User::where('mobile', $mobile)->count()){
            $this->error('注册手机号码不在存');
        }

        try {
            $sms = new Sms();
            $sms->sendCode($mobile);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        $this->success('验证码发送成功');
    }

    /**
     * 验证手机号码是否已注册
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-24
     */
    public function mobile_present(Request $request)
    {
        if(!$request->isAjax()){
            $this->redirect('[page.reset]');
        }else if(!$request->isPost()){
            $this->error('访问页面不存在');
        }

        $mobile = $request->post('mobile');
        if(empty($mobile)){
            echo('false');
        }else if(User::where('mobile', $mobile)->count()){
            echo('true');
        }else{
            echo('false');
        }
    }
}