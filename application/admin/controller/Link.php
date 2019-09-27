<?php

namespace app\admin\controller;

use think\Request;
use think\facade\Session;
use tpadmin\controller\Controller;
use app\common\model\Link as LinkModel;
use app\common\exception\ValidateException;

class Link extends Controller
{
    public function index(Request $request)
    {
        $param = $request->param();
        $paginate = LinkModel::adminPaginate($param);
        $this->assign('param', $param);
        $this->assign('paginate', $paginate);
        return $this->fetch('link/index');
    }

    public function create()
    {
        $this->assign('link', []);
        return $this->fetch('link/form');
    }

    public function save(Request $request)
    {
        if(!$request->isAjax()){
            $this->redirect('[admin.link.create]');
        }

        try{
            $data = $request->post();
            $link = LinkModel::createItem($data);
        }catch (ValidateException $e){
            return $this->error($e->getMessage(), '', ['errors' => $e->getData()]);
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }

        $message = '创建成功';
        Session::flash('success', $message);
        $this->success($message, url('[admin.link.index]'));
    }

    public function edit($id)
    {
        $link = LinkModel::find($id);

        $message = null;
        if(empty($link)){
            $message = '编辑资源不存在';
        }

        if(!empty($message)){
            Session::flash('alert', $message);
            $this->redirect('[admin.link.index]');
        }

        $this->assign('link', $link);
        return $this->fetch('link/form');
    }

    public function update(Request $request, $id)
    {
        if(!$request->isAjax()){
            $this->redirect(url('[admin.link.edit]', ['id' => $id]));
        }

        $link = LinkModel::find($id);

        if(empty($link)){
            $this->error('编辑资源不存在', '[admin.link.index]');
        }

        try{
            $data = $request->post();
            $link->updateInfo($data);
        }catch (ValidateException $e){
            $this->error($e->getMessage(), '', ['errors' => $e->getData()]);
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }

        $message = '更新成功';
        Session::flash('success', $message);
        $this->success($message, url('[admin.link.index]'));
    }

    public function delete($id)
    {
        $link = LinkModel::find($id);

        if(empty($link)){
            $this->error('删除资源不存在', '[admin.link.index]');
        }

        $link->delete();

        $message = '删除成功';
        Session::flash('success', $message);
        $this->success($message, '[admin.link.index]');
    }
}
