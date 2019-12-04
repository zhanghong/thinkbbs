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
        static::observe(\app\common\observer\Link::class);
    }

    /**
     * 查询出所有资源数据并缓存
     * @Author   zhanghong(Laifuzi)
     * @return   array
     */
    public static function selectAll()
    {
        $links = Cache::store('redis')->get(static::CACHE_KEY);
        if(!empty($links)){
            // 当缓存有数据时直接返回缓存数据
            return $links;
        }

        $links = static::order('id', 'ASC')->select();
        // 当查询结果写入缓存
        Cache::store('redis')->set(static::CACHE_KEY, $links, static::CACHE_SECONDS);
        return $links;
    }

    /**
     * 清除缓存数据
     * @Author   zhanghong(Laifuzi)
     * @return   void
     */
    public static function clearCached()
    {
        Cache::store('redis')->rm(static::CACHE_KEY);
    }

    /**
     * 后台模块搜索方法
     * @Author   zhanghong(Laifuzi)
     * @param    array              $params    搜索参数
     * @param    integer            $page_rows 每页显示数量
     * @return   Paginator
     */
    public static function adminPaginate($params = [], $page_rows = 15)
    {
        $self = static::order('id', 'ASC');
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
     * @param    array              $data 表单提交数据
     * @return   Link
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
            $link->allowField(['title', 'url'])->save($data);
        }catch (\Exception $e){
            throw new \Exception('创建资源链接失败');
        }

        return $link;
    }

    /**
     * 更新记录
     * @Author   zhanghong(Laifuzi)
     * @param    array              $data 表单提交数据
     * @return   Link
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

        $this->allowField(['title', 'url'])->save($data);
        return $this;
    }
}
