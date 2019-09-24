<?php

namespace app\common\exception;

class ValidateException extends \Exception
{
    protected $data = [];

    /**
     * 设置异常额外的数据
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-10
     * @param    array              $data [description]
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * 获取异常额外Debug数据
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-10
     * @return   array             [description]
     */
    final public function getData()
    {
        return $this->data;
    }
}