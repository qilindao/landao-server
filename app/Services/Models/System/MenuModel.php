<?php
declare(strict_types=1);

namespace App\Services\Models\System;


use App\Services\Models\BaseModel;
use App\Services\Models\Manage\RoleModel;

/**
 * 前端菜单/api权限管理 Eloquent ORM
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Class MenuModel
 * @package App\Services\Models\System;
 */
class MenuModel extends BaseModel
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'sys_menu';

    /**
     * 主键字段
     * @var string
     */
    protected $primaryKey = 'menu_id';

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

    protected $fillable = [
        'menu_id',
        'parent_id',
        'name',
        'title',
        'icon',
        'module',
        'type',
        'redirect',
        'path',
        'parent_id',
        'component',
        'auth_code',
        'order_no',
        'keep_alive',
        'hidden',
        'created_at',
        'updated_at'
    ];

    /**
     * 强制转换的属性
     *
     * @var array
     */
    protected $casts = [
        'hidden' => 'boolean',
        'keep_alive' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $hidden=[
        'pivot'
    ];

    /**
     * 角色绑定权限路由和按钮
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(RoleModel::class, 'sys_manage_role_has_menu', 'menu_id', 'role_id');
    }
}
