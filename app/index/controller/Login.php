<?php
declare (strict_types = 1);

namespace app\index\controller;

use think\facade\Session;
use app\common\model\User;
use app\common\exception\ValidateException;

class Login extends Base
{
    public function create()
    {
        $current_user = User::currentUser();
        if (!empty($current_user)) {
            return $this->redirect('/');
        }

        return $this->fetch('login/create');
    }

    public function save()
    {
        if (!$this->request->isPost() || !$this->request->isAjax()) {
            $message = '对不起，你访问页面不存在。';
            // 在跳转前把错误提示消息写入 session 里
            Session::flash('danger', $message);
            return $this->error($message, '[page.login]');
        }

        $mobile = $this->request->post('mobile');
        $password = $this->request->post('password');
        try {
            User::login($mobile, $password);
        } catch (ValidateException $e) {
            // $mobile 或 $password 错误，把错误信息返回给表单页面
            return $this->error('对不起，你填写的手机号码或密码不正确。', null, ['errors' => $e->getData()]);
        } catch (\Exception $e) {
            // 其它异常错误
            return $this->error($e->getMessage());
        }

        $currentUser = User::currentUser();
        $message = '欢迎你回来，'.$currentUser->name.'.';
        Session::set('success', $message);
        return $this->success($message, '/');
    }

    public function delete()
    {
        if ($this->request->isPost()) {
            User::logout();
        }

        return $this->redirect('/');
    }
}
