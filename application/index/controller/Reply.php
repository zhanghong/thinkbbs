<?php

namespace app\index\controller;

use think\Request;
use think\facade\Session;

use app\common\model\Reply as ReplyModel;

class Reply extends Base
{
    protected $middleware = [
        'auth'
    ];

    public function save(Request $request)
    {
        if(!$request->isAjax()){
            $this->redirect('[page.root]');
        }

        try{
            $data = $request->post();
            $reply = ReplyModel::createItem($data);
        }catch (ValidateException $e){
            $this->error($e->getMessage(), '', ['errors' => $e->getData()]);
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }

        $message = '回复成功';
        Session::set('success', $message);
        $this->success($message, url('[topic.read]', ['id' => $reply->topic_id]));
    }

    public function delete($id)
    {
        $reply = ReplyModel::find($id);

        if(empty($reply)){
            $this->error('删除回复不存在', '[page.root]');
        }else if(!$reply->canDelete()){
            $this->error('对不起，您没有权限删除该回复', url('[topic.read]', ['id' => $reply->topic_id]));
        }

        $reply->delete();

        $message = '删除成功';
        Session::set('success', $message);
        $this->success($message, url('[topic.read]', ['id' => $reply->topic_id]));
    }
}