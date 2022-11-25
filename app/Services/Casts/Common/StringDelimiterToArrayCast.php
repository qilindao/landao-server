<?php


namespace App\Services\Casts\Common;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * 以逗号分割的字符串数值转数组
 * Class StringDelimiterToArrayCast
 * @package App\Services\Casts\Common
 */
class StringDelimiterToArrayCast implements CastsAttributes
{

    public function set($model, string $key, $value, array $attributes)
    {
        return is_array($value) && count($value) > 0 ? implode(',', $value) : '';
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return trim($value) != '' ? explode(',', $value) : [];
    }
}
