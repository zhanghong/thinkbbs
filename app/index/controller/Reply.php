<?php
declare (strict_types = 1);

namespace app\index\controller;

use think\facade\Session;
use app\common\model\Reply as ReplyModel;

class Reply extends Base
{
    protected $middleware = [
        'auth'
    ];

    public function save()
    {
        if (!$this->request->isPost() || !$this->request->isAjax()) {
            $message = '对不起，你访问页面不存在。';
            Session::flash('danger', $message);
            return $this->redirect('[topic.create]');
        }

        try {
            $data = $this->request->post();
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
        $message = null;
        $flash_name = null;
        $reply = ReplyModel::find($id);

        // 默认跳转页面
        $redirect_url = '/';
        if (!$this->request->isDelete()) {
            $flash_name = 'danger';
            $message = '对不起，您访问的页面不存在。';
        }else if (empty($reply)) {
            $flash_name = 'warning';
            $message = '对不起，删除评论不存在。';
        } else {
            $redirect_url = (string) url('[topic.read]', ['id' => $reply->topic_id]);

            if (!$reply->canDelete()) {
                $flash_name = 'danger';
                $message = '对不起，您没有权限删除该评论。';
            }
        }

        if (is_null($message)) {
            // 当前用户可以删除该评论
            $reply->delete();
            $message = '评论删除成功。';
            Session::set('success', $message);
            return $this->success($message, $redirect_url);
        } else {
            // 把错误信息存储到 Session 里并跳转到其它页面
            Session::set($flash_name, $message);
            return $this->error($message, $redirect_url);
        }
    }
}
