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
            $this->redirect('[page.root]');
        }
        return $this->fetch('login/create');
    }

    public function save(Request $request)
    {
        if(!$request->isAjax()){
            $this->redirect('[page.login]');
        }else if(!$request->isPost()){
            $this->error('访问页面不存在');
        }

        $mobile = $request->post('mobile');
        $password = $request->post('password');
        try{
            User::login($mobile, $password);
        }catch (ValidateException $e){
            // $mobile 或 $password 错误，把错误信息返回给表单页面
            $this->error('验证失败', '', ['errors' => $e->getData()]);
        }catch (\Exception $e){
            // 其它异常错误
            $this->error($e->getMessage());
        }

        $message = '登录成功';
        Session::set('success', $message);
        $this->success($message, url('[page.root]'));
    }

    public function delete(Request $request)
    {
        if($request->isPost()){
            User::logout();
        }
        $this->redirect('[page.root]');
    }
}