<?php
declare(strict_types=1);

namespace App\Services\Repositories\System;

use App\Services\Enums\Common\GlobalCacheKeyEnum;
use Illuminate\Support\Facades\Cache;
use JoyceZ\LaravelLib\Repositories\BaseRepository;
use App\Services\Repositories\System\Interfaces\IDictionaryType;
use App\Services\Models\System\DictionaryTypeModel;

/**
 * 字典分类 Repository 接口实现
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Class DictionaryTypeRepo
 * @package App\Services\Repositories\System;
 */
class DictionaryTypeRepo extends BaseRepository implements IDictionaryType
{

    /**
     * @return string
     */
    public function model()
    {
        return DictionaryTypeModel::class;
    }

    /**
     * 获取字典分类列表
     * @return array
     */
    public function lists(): array
    {
        $list = Cache::tags([GlobalCacheKeyEnum::LD_DICT_TAG])->get(GlobalCacheKeyEnum::LD_DICT_GROUP_LISTS);
        if ($list) return $list;
        $list = $this->all(['dict_tid', 'type_key', 'type_name', 'expand'])->toArray();
        if ($list) Cache::tags([GlobalCacheKeyEnum::LD_DICT_TAG])->put(GlobalCacheKeyEnum::LD_DICT_GROUP_LISTS, $list);
        return $list;
    }

    public function clearCache(){
        Cache::tags(GlobalCacheKeyEnum::LD_DICT_TAG)->flush();
    }


}
