<?php
namespace app\index\controller;

use app\common\model\Sms;
class Demo
{
    public function sms()
    {
        $message = '';
        $status = false;
        $mobile = '15012345678';
        try {
            $sms = new Sms();
            $status = $sms->sendCode($mobile);
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return json(['status' => $status, 'message' => $message]);
    }
}