<?php


namespace App\Services\Enums\System;


use JoyceZ\LaravelLib\Enum\BaseEnum;

/**
 * 配置项变量类型
 * Class ConfigTypeEnum
 * @package App\Services\Enums\System
 */
class ConfigTypeEnum extends BaseEnum
{
    const CONFIG_FORM_ITEM_STRING = 'string';
    const CONFIG_FORM_ITEM_TEXTAREA = 'textarea';
    const CONFIG_FORM_ITEM_REGION = 'region';
    const CONFIG_FORM_ITEM_ARRAY = 'array';

    public static function getMap(): array
    {
        return [
            self::CONFIG_FORM_ITEM_STRING=>'文本框',
            self::CONFIG_FORM_ITEM_TEXTAREA=>'多行文本框',
            self::CONFIG_FORM_ITEM_REGION=>'国内行政区域',
            self::CONFIG_FORM_ITEM_ARRAY=>'数组',
        ];
    }

}
