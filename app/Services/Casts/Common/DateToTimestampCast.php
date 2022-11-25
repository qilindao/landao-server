<?php


namespace App\Services\Casts\Common;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * 日期转时间戳
 * Class DateToTimestampCast
 * @package App\Services\Casts\Common
 */
class DateToTimestampCast implements CastsAttributes
{
    public function set($model, string $key, $value, array $attributes)
    {
        return strtotime($value . ' 00:00:00');
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return date('Y-m-d', $value);
    }
}
