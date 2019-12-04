<?php

namespace app\index\controller;

use think\Request;
use think\facade\Session;
use app\common\model\User as UserModel;
use app\common\exception\ValidateException;

class User extends Base
{
    public function read($id)
    {
        $user = UserModel::find(intval($id));
        if (empty($user)) {
            // 当查看的用户不存在时跳转到首页
            $message = '对不起，你访问页面不存在。';
            Session::flash('danger', $message);
            return $this->redirect('[page.root]');
        }

        return $this->fetch('read', ['user' => $user]);
    }

    public function edit()
    {
        $currentUser = UserModel::currentUser();
        if (empty($currentUser)) {
            Session::flash('info', '请先登录系统。');
            return $this->redirect('[page.login]');
        }

        $user = UserModel::get($currentUser->id);
        if (empty($user)) {
            Session::flash('info', '请先登录系统。');
            return $this->redirect('[page.login]');
        }

        return $this->fetch('edit', [
          'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $currentUser = UserModel::currentUser();
        if (empty($currentUser)) {
            Session::flash('info', '请先登录系统。');
        } else if (!$request->isAjax() || !$request->isPut() ) {
            Session::flash('danger', '对不起，你访问页面不存在。');
            return $this->redirect(url('[user.read]', ['id' => $currentUser->id]));
        }

        $data = $request->post();
        try {
            $currentUser->updateProfile($data);
        } catch (ValidateException $e) {
            return $this->error('验证失败', null, ['errors' => $e->getData()]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $message = '更新个人资料成功';
        Session::set('success', $message);
        return $this->success($message, url('[user.read]', ['id' => $currentUser->id]));
    }
}
