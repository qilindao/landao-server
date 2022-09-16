<?php
declare(strict_types=1);

namespace App\Services\Models\System;

use Illuminate\Database\Eloquent\Model;

/**
 * 数据字典
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Class DictionaryModel
 * @package App\Services\Models\System;
 */
class DictionaryModel extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'sys_dictionary';

    /**
     * 主键字段
     * @var string
     */
    protected $primaryKey = 'dict_id';

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
        'dict_id',
        'type_id',
        'label',
        'is_enable',
        'order_num',
        'remark',
        'expand',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * 属性转化
     * @var array
     */
    protected $casts = [
        'is_enable' => 'boolean',
        'expand' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
