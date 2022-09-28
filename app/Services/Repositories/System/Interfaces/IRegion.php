<?php
declare(strict_types=1);

namespace App\Services\Repositories\System\Interfaces;

use JoyceZ\LaravelLib\Repositories\Interfaces\BaseInterface;

/**
 * 行政区域 Repository 接口
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Interface IRegion.
 * @package App\Services\Repositories\System\Interfaces;
 */
interface IRegion extends BaseInterface
{
    //生成本地资源文件
    public function buildLocal();
}
