<?php


namespace App\Services\Casts\Common;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use JoyceZ\LaravelLib\Helpers\StrHelper;

/**
 * ip地址转int
 * Class Ip4ConverterIntCast
 * @package App\Services\Casts\Common
 */
class Ip4ConverterIntCast implements CastsAttributes
{
    public function set($model, string $key, $value, array $attributes)
    {
        return StrHelper::ip2long($value);
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return $value > 0 ? long2ip(intval($value)) : '-';
    }
}
