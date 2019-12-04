<?php

namespace app\http\middleware;

use think\facade\Session;
use app\common\model\User;

class Auth
{
    public function handle($request, \Closure $next)
    {
        if(empty(User::currentUser())){
            Session::flash('info', '请先登录系统。');
            if($request->isAjax()){
                $result = [
                    'code' => 0,
                    'msg'  => '请先登录系统',
                    'data' => [],
                    'url'  => url('[page.login]'),
                ];
                return json($result);
            }else{
                return redirect('[page.login]');
            }
        }

        return $next($request);
    }
}
