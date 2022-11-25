<?php


namespace App\Services\Casts\Common;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * 以逗号分割的字符串数值转int数组
 * Class StringDelimiterToArrayByIntCast
 * @package App\Services\Casts\Common
 */
class StringDelimiterToArrayByIntCast implements CastsAttributes
{
    public function set($model, string $key, $value, array $attributes)
    {
        return is_array($value) && count($value) > 0 ? implode(',', $value) : '';
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return trim($value) != '' ? array_map('intval', explode(',', $value)) : [];
    }
}
