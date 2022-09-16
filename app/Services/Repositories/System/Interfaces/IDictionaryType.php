<?php
declare(strict_types=1);

namespace App\Services\Repositories\System\Interfaces;

use JoyceZ\LaravelLib\Repositories\Interfaces\BaseInterface;

/**
 * 数据字典分类 Repository 接口
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Interface IDictionaryType.
 * @package App\Services\Repositories\System\Interfaces;
 */
interface IDictionaryType extends BaseInterface
{
    /**
     * 获取分类列表，存缓存
     * @return array
     */
    public function lists(): array;
}
