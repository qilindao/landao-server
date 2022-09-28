<?php

namespace App\Services\Enums\Common;

use JoyceZ\LaravelLib\Enum\BaseEnum;

/**
 * 全局缓存key管理
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/laravel-lib/enum.html>
 *
 * Class GlobalCacheKeyEnum
 * @package App\Services\Enums\Common;
 */
class GlobalCacheKeyEnum extends BaseEnum
{

    const ZODA_DICT_GROUP_LISTS = 'zoda_dict_group_list';
    const ZODA_DICT_TAG = "zoda_dict_tag";
    const ZODA_SYS_CONFIG_TAG_CACHE_KEY = "zoda_config_tag";
    const ZODA_SYS_CONFIG_LIST_CACHE_KEY = "zoda_config_list";

    public static function getMap(): array
    {
        return [
            self::ZODA_DICT_TAG => '字典缓存标签',
            self::ZODA_DICT_GROUP_LISTS => '字典分类',
            self::ZODA_SYS_CONFIG_TAG_CACHE_KEY => '系统配置标签',
            self::ZODA_SYS_CONFIG_LIST_CACHE_KEY => '所有系统配置',
        ];
    }
}
