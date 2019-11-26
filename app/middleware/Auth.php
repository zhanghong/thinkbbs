<?php
declare (strict_types = 1);

namespace app\middleware;

use think\facade\Session;
use app\common\model\User;

class Auth
{
    public function handle($request, \Closure $next)
    {
        if (empty(User::currentUser())) {
            $url = (string) url('[page.login]');
            $message = '请先登录系统。';
            Session::flash('info', $message);
            if ($request->isAjax()) {
                $result = [
                    'code' => 0,
                    'msg'  => $message,
                    'data' => [],
                    'url'  => $url,
                ];
                return json($result);
            } else {
                return redirect($url);
            }
        }

        return $next($request);
    }
}
