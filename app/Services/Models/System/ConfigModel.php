<?php
declare(strict_types=1);

namespace App\Services\Models\System;

use App\Services\Casts\System\ConfigContentCast;
use App\Services\Casts\System\ConfigRuleCast;
use App\Services\Casts\System\ConfigValueCast;
use Illuminate\Database\Eloquent\Model;

/**
 * 请说明具体哪块业务的 Eloquent ORM
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Class ConfigModel
 * @package App\Services\Models\System;
 */
class ConfigModel extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'sys_config';

    /**
     * 主键字段
     * @var string
     */
    protected $primaryKey = 'conf_id';

    /**
     * 指示是否自动维护时间戳
     * @var bool
     */
    public $timestamps = false;


    /**
     * 字段信息
     * @var array
     */
    protected $fillable = [
        'conf_id',
        'name',
        'group',
        'title',
        'tip',
        'type',
        'value',
        'content',
        'rule',
        'extend',
        'is_del',
        'weigh',
    ];

    /**
     * 属性转化
     * @var array
     */
    protected $casts = [
        'is_del' => 'boolean',
        'value' => ConfigValueCast::class,
        'content' => ConfigContentCast::class,
        'rule' => ConfigRuleCast::class
    ];
}
