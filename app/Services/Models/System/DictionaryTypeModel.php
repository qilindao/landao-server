<?php
declare(strict_types=1);

namespace App\Services\Models\System;

use App\Services\Models\BaseModel;

/**
 * 数据字典分类
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Class DictionaryTypeModel
 * @package App\Services\Models\System;
 */
class DictionaryTypeModel extends BaseModel
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'sys_dictionary_type';

    /**
     * 主键字段
     * @var string
     */
    protected $primaryKey = 'dict_tid';

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
        'dict_tid',
        'type_key',
        'type_name',
        'expand',
        'created_at',
        'updated_at',
    ];

    /**
     * 属性转化
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'expand' => 'array',
    ];
}
