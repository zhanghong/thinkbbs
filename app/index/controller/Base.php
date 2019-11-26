<?php
declare (strict_types = 1);

namespace app\index\controller;

use think\App;
use think\app\Url;
use think\Validate;
use think\facade\View;
use think\exception\ValidateException;
use app\common\model\Config as ConfigModel;

abstract class Base
{
    protected $request;
    protected $app;
    protected $middleware = [];

    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    protected function initialize()
    {
        if (!$this->request->isAjax()) {
            // 读取站点设置信息
            $site = ConfigModel::siteSetting();
            View::assign('site', $site);
        }
    }

    /**
     * 解析和获取模板内容 用于输出
     * @Author   zhanghong(Laifuzi)
     * @param    string             $template 模板文件名或者内容
     * @param    array              $vars     模板变量
     * @return   string
     */
    protected function fetch(string $template = '', array $vars = []): string
    {
        return View::fetch($template, $vars);
    }

    /**
     * 操作成功跳转的快捷方法
     * @Author   zhanghong(Laifuzi)
     * @param    string             $msg  提示信息
     * @param    string/Route       $url  跳转的URL地址
     * @param    string             $data 返回的数据
     * @return
     */
    protected function success($msg = '', $url = null, $data = '')
    {
        return $this->jump(1, $msg, $url, $data);
    }

    /**
     * 操作失败跳转的快捷方法
     * @Author   zhanghong(Laifuzi)
     * @param    string             $msg  提示信息
     * @param    string/Route       $url  跳转的URL地址
     * @param    string             $data 返回的数据
     * @return
     */
    protected function error($msg = '', $url = null, $data = '')
    {
        return $this->jump(0, $msg, $url, $data);
    }

    /**
     * URL重定向
     * @Author   zhanghong(Laifuzi)
     * @param    string/Route       $url    跳转的URL表达式
     * @param    int                $code   http code
     * @return   void
     */
    protected function redirect($url, int $code = 302)
    {
        if ($url instanceof Url) {
            $url = (string) $url;
        } else if (!(strpos($url, '://') || 0 === strpos($url, '/'))) {
            // buildUrl 方法返回值是 think\app\Url 对象，所以必须强制转化成字符串
            $url = (string) $this->app->route->buildUrl($url);
        }

        return redirect($url);
    }

    /**
     * 操作跳转方法
     * @Author   zhanghong(Laifuzi)
     * @param    int                $code 是否成功
     * @param    string             $msg  提示信息
     * @param    string/Route       $url  跳转的URL地址
     * @param    string             $data 返回的数据
     * @return
     */
    private function jump($code, $msg = '', $url = null, $data = '')
    {
        if (is_null($url)) {
            $url = $this->request->isAjax() ? '' : 'javascript:history.back(-1);';
        } else if ($url instanceof Url) {
            $url = (string) $url;
        } else if (!(strpos($url, '://') || 0 === strpos($url, '/'))) {
            // buildUrl 方法返回值是 think\app\Url 对象，所以必须强制转化成字符串
            $url = (string) $this->app->route->buildUrl($url);
        }

        if ($this->request->isAjax()){
            return json([
                'code' => $code,
                'msg' => $msg,
                'data' => $data,
                'url' => $url,
            ]);
        }

        return $this->redirect($url);
    }
}
