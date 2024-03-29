<?php
declare(strict_types=1);

namespace App\Services\Repositories\System;

use Illuminate\Support\Facades\File;
use JoyceZ\LaravelLib\Helpers\TreeHelper;
use JoyceZ\LaravelLib\Repositories\BaseRepository;
use App\Services\Models\System\RegionModel;

/**
 * 请说明具体哪块业务的 Repository 接口实现
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Class RegionRepo
 * @package App\Services\Repositories\System;
 */
class RegionRepo extends BaseRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return RegionModel::class;
    }

    /**
     * 生成静态json数据
     */
    public function buildLocal()
    {
        $region = $this->getRegionTree();
        File::put('resources/region.json', json_encode($region));
    }

    public function getRegionTree()
    {
        $district = $this->column('region_id as value,parent_id,fullname as label', [], 'region_id');
        return TreeHelper::listToTree($district, 0, 'value', 'parent_id');
    }

    /**
     * 根据区域id数组，获取
     * @param array $ids
     * @return array
     */
    public function getRegionTreeByIds(array $ids): array
    {
        $lists = $this->findWhereIn('region_id', $ids, ['region_id as value', 'parent_id', 'fullname as label', 'depth']);
        return TreeHelper::listToTree($lists->toArray(), 0, 'value', 'parent_id');
    }


}
