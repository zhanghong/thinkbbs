<?php

namespace app\index\controller;

use think\Request;
use think\facade\Session;
use app\common\model\Reply as ReplyModel;
use app\common\exception\ValidateException;

class Reply extends Base
{
    protected $middleware = [
        'auth'
    ];

    public function save(Request $request)
    {
        if (!$request->isPost() || !$request->isAjax()) {
            $message = '对不起，你访问页面不存在。';
            Session::flash('danger', $message);
            return $this->redirect('[topic.create]');
        }

        try {
            $data = $request->post();
            $reply = ReplyModel::createItem($data);
        } catch (ValidateException $e) {
            return $this->error($e->getMessage(), null, ['errors' => $e->getData()]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $message = '回复成功。';
        Session::set('success', $message);
        return $this->success($message, url('[topic.read]', ['id' => $reply->topic_id]));
    }

    public function delete($id)
    {

    }
}
