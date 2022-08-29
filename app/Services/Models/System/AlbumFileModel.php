<?php
declare(strict_types=1);

namespace App\Services\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * 请说明具体哪块业务的 Eloquent ORM
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Class AlbumFileModel
 * @package App\Services\Models\System;
 */
class AlbumFileModel extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'sys_album_file';

    /**
     * 主键字段
     * @var string
     */
    protected $primaryKey = 'file_id';

    /**
     * 指示是否自动维护时间戳
     * @var bool
     */
    public $timestamps = true;

    /**
     * 模型日期列的存储格式。
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * 字段信息
     * @var array
     */
    protected $fillable = ['file_id', 'album_id', 'file_name', 'original_name', 'file_path', 'file_md5', 'file_size', 'file_ext', 'file_type', 'mime_type', 'file_ip', 'created_at', 'updated_at'];

    /**
     * 属性转化
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends=[
        'file_url'
    ];

    public function getFileUrlAttribute()
    {
        return asset(Storage::url($this->file_path));
    }
}
