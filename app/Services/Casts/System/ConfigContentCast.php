<?php


namespace App\Services\Casts\System;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * 配置项选项内容转化
 * Class ConfigContentCast
 * @package App\Services\Casts\System
 */
class ConfigContentCast implements CastsAttributes
{
    protected $needContent = ['radio', 'checkbox', 'select', 'selects'];

    public function set($model, string $key, $value, array $attributes)
    {
        if (in_array($attributes['type'], $this->needContent)) {
            $arr = json_encode($value, true);
            return $arr ?: [];
        } else {
            return '';
        }
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
        if (!isset($attributes['type'])) return '';
        if (in_array($attributes['type'], $this->needContent)) {
            $arr = json_decode($value, true);
            return $arr ?: [];
        } else {
            return '';
        }
    }


}
