<?php
declare(strict_types=1);

namespace App\Services\Repositories\System\Interfaces;

use JoyceZ\LaravelLib\Repositories\Interfaces\BaseInterface;

/**
 * 附件操作
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Interface IAlbumFile.
 * @package App\Services\Repositories\System\Interfaces;
 */
interface IAlbumFile extends BaseInterface
{
    /**
     * 上传图片到本地
     * @param $request
     * @return array
     */
    public function doLocalUpload($request): array;

    /**
     * 附件列表
     * @param array $params
     * @return array
     */
    public function getPage(array $params): array;
}
