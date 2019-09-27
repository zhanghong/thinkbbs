<?php

namespace app\common\model;

use think\Model;
use think\facade\Cache;
use app\common\validate\Link as Validate;
use app\common\exception\ValidateException;

class Link extends Model
{
    // 缓存主键
    protected const CACHE_KEY = 'links';
    // 缓存有效时长（秒）
    protected const CACHE_SECONDS = 1440 * 60;

    protected static function init()
    {
        self::observe(\app\common\observer\Link::class);
    }

    /**
     * 查询出所有资源数据并缓存
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-04
     * @return   array              [description]
     */
    public static function selectAll()
    {
        $links = Cache::store('redis')->get(self::CACHE_KEY);
        if(!empty($links)){
            // 当缓存有数据时直接返回缓存数据
            return $links;
        }

        $links = self::order('id', 'ASC')->select();
        // 当查询结果写入缓存
        Cache::store('redis')->set(self::CACHE_KEY, $links, self::CACHE_SECONDS);
        return $links;
    }

    /**
     * 清除缓存数据
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-04
     * @return   [type]             [description]
     */
    public static function clearCached()
    {
        Cache::store('redis')->rm(self::CACHE_KEY);
    }

    /**
     * 后台模块搜索方法
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-28
     * @param    array              $params    [description]
     * @param    integer            $page_rows [description]
     * @return   [type]                        [description]
     */
    public static function adminPaginate($params = [], $page_rows = 15)
    {
        $self = self::order('id', 'ASC');
        $map = [];
        foreach ($params as $name => $text) {
            $text = trim($text);
            switch ($name) {
                case 'keyword':
                    if(!empty($text)){
                        $like_text = '%'.$text.'%';
                        $self = $self->whereLike('title', $like_text);
                    }
                    break;
            }
        }
        return $self->paginate($page_rows, false, ['query' => $params]);
    }

    /**
     * 创建记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-21
     * @param    array              $data 表单提交数据
     * @return   Topic                    [description]
     */
    public static function createItem($data)
    {
        $validate = new Validate;
        if(!$validate->batch(true)->check($data)){
            $e = new ValidateException('数据验证失败');
            $e->setData($validate->getError());
            throw $e;
        }

        try{
            $link = new self;
            $link->allowField(true)->save($data);
        }catch (\Exception $e){
            throw new \Exception('创建资源链接失败');
        }

        return $link;
    }

    /**
     * 更新记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2019-06-21
     * @param    array              $data [description]
     * @return   [type]                   [description]
     */
    public function updateInfo($data)
    {
        $data['id'] = $this->id;

        $validate = new Validate;
        if(!$validate->batch(true)->check($data)){
            $e = new ValidateException('数据验证失败');
            $e->setData($validate->getError());
            throw $e;
        }

        $this->allowField(true)->save($data, ['id' => $this->id]);
        return $this;
    }
}
