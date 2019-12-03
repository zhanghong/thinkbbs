<?php

namespace app\index\controller;

use think\Request;
use think\facade\Session;
use app\common\model\User;
use app\common\exception\ValidateException;

class Login extends Base
{
    public function create()
    {
        $current_user = User::currentUser();
        if(!empty($current_user)){
            // 用户已登录
            return $this->redirect('[page.root]');
        }
        return $this->fetch('login/create');
    }

    public function save(Request $request)
    {
        if(!$request->isAjax() || !$request->isPost()){
            $message = '对不起，你访问页面不存在。';
            // 在跳转前把错误提示消息写入 session 里
            Session::flash('danger', $message);
            return $this->error($message, '[page.login]');
        }

        $mobile = $request->post('mobile');
        $password = $request->post('password');
        try{
            User::login($mobile, $password);
        }catch (ValidateException $e){
            // $mobile 或 $password 错误，把错误信息返回给表单页面
            return $this->error('对不起，你填写的手机号码或密码不正确。', null, ['errors' => $e->getData()]);
        }catch (\Exception $e){
            // 其它异常错误
            return $this->error($e->getMessage());
        }

        $currentUser = User::currentUser();
        $message = '欢迎你回来，'.$currentUser->name.'.';
        Session::set('success', $message);
        return $this->success($message, '[page.root]');
    }

    public function delete(Request $request)
    {
        if($request->isPost()){
            User::logout();
        }
        return $this->redirect('[page.root]');
    }
}
