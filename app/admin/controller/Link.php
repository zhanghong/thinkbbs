<?php

namespace app\admin\controller;

use think\facade\Session;
use tpadmin\controller\Controller;
use app\common\model\Link as LinkModel;
use app\common\exception\ValidateException;

class Link extends Controller
{
    public function index()
    {
        $param = $this->request->param();
        return $this->fetch('link/index', [
            'param' => $param,
            'paginate' => LinkModel::adminPaginate($param),
        ]);
    }

    public function create()
    {
        return $this->fetch('link/form', [
            'link' => [],
        ]);
    }

    public function save()
    {
        if (!$this->request->isAjax()) {
            return $this->redirect('[admin.link.create]');
        }

        try {
            $data = $this->request->post();
            $link = LinkModel::createItem($data);
        } catch (ValidateException $e) {
            return $this->error($e->getMessage(), null, ['errors' => $e->getData()]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $message = '创建成功';
        Session::flash('success', $message);
        return $this->success($message, '[admin.link.index]');
    }

    public function edit($id)
    {
        $link = LinkModel::find($id);

        $message = null;
        if (empty($link)) {
            $message = '编辑资源不存在';
        }

        if (!empty($message)) {
            Session::flash('alert', $message);
            return $this->redirect('[admin.link.index]');
        }

        return $this->fetch('link/form', [
            'link' => $link,
        ]);
    }

    public function update($id)
    {
        if (!$this->request->isAjax()) {
            return $this->redirect(url('[admin.link.edit]', ['id' => $id]));
        }

        $link = LinkModel::find($id);

        if (empty($link)) {
            return $this->error('编辑资源不存在', '[admin.link.index]');
        }

        try {
            $data = $this->request->post();
            $link->updateInfo($data);
        } catch (ValidateException $e) {
            return $this->error($e->getMessage(), null, ['errors' => $e->getData()]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        $message = '更新成功';
        Session::flash('success', $message);
        return $this->success($message, '[admin.link.index]');
    }

    public function delete($id)
    {
        $link = LinkModel::find($id);

        if (empty($link)) {
            return $this->error('删除资源不存在', '[admin.link.index]');
        }

        $link->delete();

        $message = '删除成功';
        Session::flash('success', $message);
        return $this->success($message, '[admin.link.index]');
    }
}
