<?php
declare(strict_types=1);

namespace App\Services\Repositories\System;

use JoyceZ\LaravelLib\Repositories\BaseRepository;
use App\Services\Repositories\System\Interfaces\IAlbum;
use App\Services\Models\System\AlbumModel;

/**
 * 附件专辑分类
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Class AlbumRepo
 * @package App\Services\Repositories\System;
 */
class AlbumRepo extends BaseRepository implements IAlbum
{

    /**
     * @return string
     */
    public function model()
    {
        return AlbumModel::class;
    }
}
