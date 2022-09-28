<?php
declare(strict_types=1);

namespace App\Services\Repositories\System;

use App\Services\Casts\System\ConfigValueCast;
use App\Services\Enums\Common\GlobalCacheKeyEnum;
use Illuminate\Support\Facades\Cache;
use JoyceZ\LaravelLib\Repositories\BaseRepository;
use App\Services\Repositories\System\Interfaces\IConfig;
use App\Services\Models\System\ConfigModel;

/**
 * 请说明具体哪块业务的 Repository 接口实现
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Class ConfigRepo
 * @package App\Services\Repositories\System;
 */
class ConfigRepo extends BaseRepository implements IConfig
{

    /**
     * @return string
     */
    public function model()
    {
        return ConfigModel::class;
    }

    /**
     * 根据配置名称，获取配信详细信息
     * @param string $name
     * @return array
     */
    public function getConfigByName(string $name): array
    {
        $config = $this->getConfigList();
        return $config ? (isset($config[$name]) ? $config[$name] : []) : [];
    }

    /**
     * 根据配置名称，获取配置值
     * @param string $name
     * @return array
     */
    public function getConfigValueByName(string $name = ''): array
    {
        $config = $this->getConfigByName($name);
        return $config['value'] ?? [];
    }

    /**
     * 获取配置列表
     * @return array
     */
    public function getConfigList(): array
    {
        $configList = Cache::tags([GlobalCacheKeyEnum::ZODA_SYS_CONFIG_TAG_CACHE_KEY])->get(GlobalCacheKeyEnum::ZODA_SYS_CONFIG_LIST_CACHE_KEY);
        if ($configList) {
            return $configList;
        }
        $configListRet = $this->orderBy('weigh', 'desc')->all(['conf_id', 'name', 'tip', 'title', 'type', 'group', 'value', 'content', 'rule', 'extend', 'is_del']);
        $configList = [];
        foreach ($configListRet->toArray() as $key => $item) {
            $configList[$item['name']] = $item;
        }
        if ($configList) {
            Cache::tags([GlobalCacheKeyEnum::ZODA_SYS_CONFIG_TAG_CACHE_KEY])->put(GlobalCacheKeyEnum::ZODA_SYS_CONFIG_LIST_CACHE_KEY, $configList);
        }
        return $configList;
    }

    /**
     * 按分组获取列表
     * @return array
     */
    public function getConfigListByGroup(): array
    {
        $configGroup = $this->getConfigValueByName('config_group');
        $configList = $this->getConfigList();
        $list = [];
        foreach ($configList as $key => $item) {
            $list[$item['group']][] = $item;
        }
        $groupList = [];
        foreach ($configGroup as $key => $item) {
            $groupList[$item['key']]['name'] = $item['key'];
            $groupList[$item['key']]['title'] = $item['value'];
            $groupList[$item['key']]['list'] = $list[$item['key']] ?? [];

        }
        return $groupList;
    }

    /**
     * 根据分组名称，获取配置分组
     * @param string $name
     * @return array
     */
    public function getConfigByGroup(string $name): array
    {
        return $this->getConfigListByGroup()[$name] ?? [];
    }

    public function setValueAttributes($key, $value, $attributes)
    {
        $valueCast = new ConfigValueCast();
        return $valueCast->set($this->model, $key, $value, $attributes);
    }

    /**
     * 清楚配置缓存
     */
    public function clearConfig()
    {
        Cache::tags(GlobalCacheKeyEnum::ZODA_SYS_CONFIG_TAG_CACHE_KEY)->flush();
    }


}
