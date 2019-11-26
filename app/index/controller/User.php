<?php
declare (strict_types = 1);

namespace app\index\controller;

use think\facade\Session;
use app\common\model\User as UserModel;
use app\common\model\Topic as TopicModel;
use app\common\exception\ValidateException;

class User extends Base
{
    protected $middleware = [
        'auth' => ['except' => ['read']],
    ];

    public function read($id)
    {
        $user = UserModel::find(intval($id));
        if (empty($user)) {
            // 当查看的用户不存在时跳转到首页
            $message = '对不起，你访问页面不存在。';
            Session::flash('danger', $message);
            return $this->redirect('/');
        }

        return $this->fetch('read', [
            'user' => $user,
            'topic_paginate' => TopicModel::minePaginate(['user_id' => $user->id], 5),
        ]);
    }

    public function edit()
    {
        $currentUser = UserModel::currentUser();

        return $this->fetch('edit', [
          'user' => $currentUser->refresh(),
        ]);
    }

    public function update()
    {
        $currentUser = UserModel::currentUser();

        if (!$this->request->isAjax() || !$this->request->isPut() ) {
            Session::flash('danger', '对不起，你访问页面不存在。');
            return $this->redirect(url('[user.read]', ['id' => $currentUser->id]));
        }

        $data = $this->request->post();
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
