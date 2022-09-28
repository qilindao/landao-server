<?php


namespace App\Services\Casts\System;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * 配置项值转化
 * Class ConfigValueCast
 * @package App\Services\Casts\System
 */
class ConfigValueCast implements CastsAttributes
{

    protected $jsonDecodeType = ['checkbox', 'array', 'selects'];

    /**
     * 将取出的数据进行转换
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array|mixed
     */
    public function get($model, $key, $value, $attributes)
    {
        if (!isset($attributes['type'])) return $value;
        if (in_array($attributes['type'], $this->jsonDecodeType)) {
            $arr = json_decode($value, true);
            return $arr ?: [];
        } elseif ($attributes['type'] == 'switch') {
            return (bool)$value;
        } elseif ($attributes['type'] == 'editor') {
            return !$value ? '' : htmlspecialchars_decode($value);
        } elseif ($attributes['type'] == 'region') {
            if ($value == '') {
                return [];
            }
            if (!is_array($value)) {
                return explode(',', $value);
            }
            return $value;
        } else {
            return $value ?: '';
        }
    }

    /**
     * 转换成将要进行存储的值
     * 注意：需要其他字段值来判断的情况下。在传入字段的时候，优先级要在需要转换的字段前面。否则会报错，取不到其他字段
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed|string
     */
    public function set($model, $key, $value, $attributes)
    {
        if (in_array($attributes['type'], $this->jsonDecodeType)) {
            return $value ? json_encode($value) : '';
        } elseif ($attributes['type'] == 'switch') {
            return $value ? '1' : '0';
        } elseif ($attributes['type'] == 'time') {
            return $value ? date('H:i:s', strtotime($value)) : '';
        } elseif ($attributes['type'] == 'region') {
            if ($value && is_array($value)) {
                return implode(',', $value);
            }
            return $value ?: '';
        }
        return $value;
    }
}
