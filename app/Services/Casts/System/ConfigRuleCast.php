<?php


namespace App\Services\Casts\System;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * 配置项验证规则
 * Class ConfigRuleCast
 * @package App\Services\Casts\System
 */
class ConfigRuleCast implements CastsAttributes
{
    public function set($model, string $key, $value, array $attributes)
    {
        return is_array($value) ? implode(',', $value) : '';
    }

    /**
     * 将取出的数据进行转换
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed|void
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return trim($value) != '' ? explode(',', $value) : [];
    }
}
