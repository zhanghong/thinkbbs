<?php

namespace app\common\exception;

class ValidateException extends \Exception
{
    protected $data = [];

    /**
     * 设置异常额外的数据
     * @Author   zhanghong(Laifuzi)
     * @param    array              $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * 获取异常额外Debug数据
     * @Author   zhanghong(Laifuzi)
     * @return   array
     */
    final public function getData(): array
    {
        return $this->data;
    }
}
