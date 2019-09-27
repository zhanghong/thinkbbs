<?php

namespace app\common\observer;

use app\common\model\Link as LinkModel;

class Link
{
    public function afterWrite(LinkModel $topic)
    {
        LinkModel::clearCached();
    }

    public function afterDelete(LinkModel $topic)
    {
        LinkModel::clearCached();
    }
}
