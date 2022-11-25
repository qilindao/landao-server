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

    const LD_DICT_GROUP_LISTS = 'landao_dict_group_list';
    const LD_DICT_TAG = "landao_dict_tag";
    const LD_SYS_CONFIG_TAG_CACHE_KEY = "landao_config_tag";
    const LD_SYS_CONFIG_LIST_CACHE_KEY = "landao_config_list";
    const LD_DICT_LIST_BY_GROUP = 'landao_dict_list_by_group';

    public static function getMap(): array
    {
        return [
            self::LD_DICT_GROUP_LISTS => '字典缓存标签',
            self::LD_DICT_TAG => '字典分类',
            self::LD_SYS_CONFIG_TAG_CACHE_KEY => '系统配置标签',
            self::LD_SYS_CONFIG_LIST_CACHE_KEY => '所有系统配置',
            self::LD_DICT_LIST_BY_GROUP => '所有系统配置',
        ];
    }
}
