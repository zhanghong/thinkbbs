<?php

namespace app\http\middleware;

use app\common\model\User;

class Auth
{
    public function handle($request, \Closure $next)
    {
        if(empty(User::currentUser())){
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
