<?php
declare(strict_types=1);

namespace App\Services\Repositories\System\Interfaces;

use JoyceZ\LaravelLib\Repositories\Interfaces\BaseInterface;

/**
 * 请说明具体哪块业务的 Repository 接口
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Interface IConfig.
 * @package App\Services\Repositories\System\Interfaces;
 */
interface IConfig extends BaseInterface
{

    /**
     * 根据配置名，获取配置信息
     * @param string $name
     * @return array
     */
    public function getConfigByName(string $name): array;

}
