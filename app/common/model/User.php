<?php
declare (strict_types = 1);

namespace app\common\model;

use think\Model;

/**
 * @mixin think\Model
 */
class User extends Model
{
    /**
     * 验证字段值是否唯一
     * @Author   zhanghong(Laifuzi)
     * @param    array              $data 验证字段和字段值
     * @param    int                $id   用户ID
     * @return   bool
     */
    public static function checkFieldUnique(array $data, int $id = 0): bool
    {
        $field_name = null;
        // 验证字段名必须存在
        if (!isset($data['field'])) {
            return false;
        }
        // 验证字段名
        $field_name = $data['field'];

        // 验证字段值必须存在
        if (!isset($data[$field_name])) {
            return false;
        }
        $field_value = $data[$field_name];

        $query = static::where($field_name, $field_value);
        if ($id > 0) {
            $query->where('id', '<>', $id);
        }

        if ($query->count()) {
            return false;
        }

        return true;
    }
}
