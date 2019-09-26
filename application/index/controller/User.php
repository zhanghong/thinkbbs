<?php

namespace app\index\controller;

use think\Request;
use think\facade\Session;
use app\common\model\User as UserModel;
use app\common\model\Topic as TopicModel;
use app\common\model\Reply as ReplyModel;
use app\common\exception\ValidateException;

class User extends Base
{
    protected $middleware = [
        'auth' => ['except' => ['read']],
    ];

    public function read(Request $request, $id)
    {
        $user = UserModel::find(intval($id));
        if(empty($user)){
            // 当查看的用户不存在时跳转到首页
            $this->redirect('[page.root]');
        }

        $this->assign('user', $user);

        $param_tab = $request->param('tab');
        if($param_tab == 'replies'){
            $is_replies = true;
            $reply_paginate = ReplyModel::minePaginate(['user_id' => $user->id], 5);
            $this->assign('reply_paginate', $reply_paginate);
        }else{
            $is_replies = false;
            $topic_paginate = TopicModel::minePaginate(['user_id' => $user->id], 5);
            $this->assign('topic_paginate', $topic_paginate);
        }

        $this->assign('is_replies', $is_replies);

        return $this->fetch('read');
    }

    public function edit()
    {
        $currentUser = UserModel::currentUser();
        $user = UserModel::find($currentUser->id);
        if(empty($user)){
            $this->redirect('[page.root]');
        }

        $this->assign('user', $user);
        return $this->fetch('edit');
    }

    public function update(Request $request)
    {
        $currentUser = UserModel::currentUser();
        if(!$request->isAjax()){
            $this->redirect('[user.read]', ['id' => $currentUser->id]);
        }

        $data = $request->post();
        try{
            $currentUser->updateProfile($data);
        }catch (ValidateException $e){
            $this->error('验证失败', '', ['errors' => $e->getData()]);
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }

        $message = '更新个人资料成功';
        Session::set('success', $message);
        $this->success($message, url('[user.read]', ['id' => $currentUser->id]));
    }
}