<?php
declare (strict_types = 1);

namespace app\index\controller;

use app\common\model\Sms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class Index extends Base
{
    public function index()
    {
        return view('index');
    }

    public function sms()
    {
        $mobile = '15012345678';
        $status = true;
        $message = '短信发送成功';
        try {
            $sms = new Sms();
            $sms->sendCode($mobile);
        } catch (NoGatewayAvailableException $e) {
            $status = false;
            $message = $e->getMessage();
        } catch (\Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return json(['status' => $status, 'message' => $message]);
    }
}
