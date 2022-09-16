<?php
declare(strict_types=1);

namespace App\Services\Repositories\System;

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
}
