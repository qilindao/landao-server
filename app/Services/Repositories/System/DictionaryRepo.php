<?php
declare(strict_types=1);

namespace App\Services\Repositories\System;

use App\Services\Enums\Common\GlobalCacheKeyEnum;
use Illuminate\Support\Facades\Cache;
use JoyceZ\LaravelLib\Repositories\BaseRepository;
use App\Services\Repositories\System\Interfaces\IDictionary;
use App\Services\Models\System\DictionaryModel;

/**
 * 请说明具体哪块业务的 Repository 接口实现
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Class DictionaryRepo
 * @package App\Services\Repositories\System;
 */
class DictionaryRepo extends BaseRepository implements IDictionary
{

    /**
     * @return string
     */
    public function model()
    {
        return DictionaryModel::class;
    }

    /**
     * 根据字典类型进行数据分组
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getAllDictByGroup(): array
    {
        $dictionary = Cache::tags([GlobalCacheKeyEnum::ZODA_DICT_TAG])->get(GlobalCacheKeyEnum::ZODA_DICT_LIST_BY_GROUP);
        if ($dictionary) {
            return $dictionary;
        }
        $dictTypeRepo = $this->app->make(DictionaryTypeRepo::class);
        $groupList = $dictTypeRepo->lists();
        $group = array_column($groupList, 'type_key', 'dict_tid');
        $dict = [];
        $lists = $this->getList();
        foreach ($lists as $item) {
            $dict[$group[$item['type_id']]][] = $item;
        }
        if($dict){
            Cache::tags([GlobalCacheKeyEnum::ZODA_DICT_TAG])->put(GlobalCacheKeyEnum::ZODA_DICT_LIST_BY_GROUP, $dict);
        }
        return $dict;
    }

    /**
     * 获取所有字典数据
     * @return array
     */
    public function getList()
    {
        $list = $this->all(['dict_id', 'type_id', 'label', 'is_enable', 'expand']);
        $dict = [];
        foreach ($list as $item) {
            $dict[] = [
                'label' => $item['label'],
                'value' => $item['dict_id'],
                'type_id' => $item['type_id'],
                'is_enable' => $item['is_enable'],
                'expand' => $item['expand'],
            ];
        }
        return $dict;
    }

    /**
     * 清除缓存
     */
    public function clearCache()
    {
        Cache::tags(GlobalCacheKeyEnum::ZODA_DICT_TAG)->flush();
    }
}
