<?php
declare(strict_types=1);

namespace App\Services\Models\System;

use App\Services\Models\BaseModel;

/**
 * 行政区域 Eloquent ORM
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Class RegionModel
 * @package App\Services\Models\System;
 */
class RegionModel extends BaseModel
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'sys_region';

    /**
     * 主键字段
     * @var string
     */
    protected $primaryKey = 'region_id';

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
    protected $fillable = [
        'region_id',
        'parent_id',
        'depth',
        'name',
        'fullname',
        'pinyin',
        'pinyin_arr',
        'latitude',
        'longitude',
    ];

    /**
     * 属性转化
     * @var array
     */
    protected $casts = [
    ];
}
