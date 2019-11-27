<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;
use think\facade\Cache;
use app\common\validate\Link as Validate;
use app\common\exception\ValidateException;

/**
 * @mixin think\Model
 */
class Link extends Model
{
    // 缓存主键
    protected const CACHE_KEY = 'links';
    // 缓存有效时长（秒）
    protected const CACHE_SECONDS = 1440 * 60;

    /**
     * 查询出所有资源数据并缓存
     * @Author   zhanghong(Laifuzi)
     * @return   array
     */
    public static function selectAll()
    {
        $links = Cache::store('redis')->get(static::CACHE_KEY);
        if (!empty($links)) {
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
     * @return   boolean
     */
    protected static function clearCached()
    {
        Cache::store('redis')->delete(static::CACHE_KEY);
        return true;
    }

    public static function onAfterWrite(Link $link)
    {
        static::clearCached();
    }

    public static function onAfterDelete(Link $link)
    {
        static::clearCached();
    }

    /**
     * 管理员后台搜索方法
     * @Author   zhanghong(Laifuzi)
     * @param    array              $params    请求参数
     * @param    integer            $page_rows 每页显示数量
     * @return   Paginator
     */
    public static function adminPaginate($params = [], $page_rows = 15)
    {
        $static = static::order('id', 'ASC');
        $map = [];
        foreach ($params as $name => $text) {
            $text = trim($text);
            switch ($name) {
                case 'keyword':
                    if (!empty($text)) {
                        $like_text = '%'.$text.'%';
                        $static = $static->whereLike('title', $like_text);
                    }
                    break;
            }
        }
        return $static->paginate($page_rows, false, ['query' => $params]);
    }

    /**
     * 创建记录
     * @Author   zhanghong(Laifuzi)
     * @param    array              $data 表单提交数据
     * @return   Topic
     */
    public static function createItem($data)
    {
        $validate = new Validate;
        if (!$validate->batch(true)->check($data)) {
            $e = new ValidateException('数据验证失败');
            $e->setData($validate->getError());
            throw $e;
        }

        try {
            $link = new static;
            $link->allowField(['title', 'url'])->save($data);
        } catch (\Exception $e) {
            throw new \Exception('创建资源链接失败');
        }

        return $link;
    }

    /**
     * 更新记录
     * @Author   zhanghong(Laifuzi)
     * @param    array              $data 更新数据
     * @return   Topic
     */
    public function updateInfo($data)
    {
        $data['id'] = $this->id;

        $validate = new Validate;
        if (!$validate->batch(true)->check($data)) {
            $e = new ValidateException('数据验证失败');
            $e->setData($validate->getError());
            throw $e;
        }

        try {
            $this->allowField(['title', 'url'])->save($data);
        } catch (\Exception $e) {
            throw new \Exception('创建资源链接失败');
        }

        return $this;
    }
}
