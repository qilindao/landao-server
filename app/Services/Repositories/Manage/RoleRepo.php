<?php
declare (strict_types=1);

namespace App\Services\Repositories\Manage;


use App\Services\Models\Manage\ManageModel;
use App\Services\Models\Manage\RoleHasMenuModel;
use App\Services\Models\Manage\RoleModel;
use App\Services\Repositories\System\MenuRepo;
use Illuminate\Support\Arr;
use JoyceZ\LaravelLib\Repositories\BaseRepository;

/**
 * 实现角色接口
 * @author joyecZhang <zhangwei762@163.com>
 * Class RoleRepo
 * @package App\Services\Repositories\Manage
 */
class RoleRepo extends BaseRepository
{
    public function model()
    {
        return RoleModel::class;
    }

    /**
     * 获取角色列表
     * @param array $params
     * @param string $orderBy
     * @param string $sort
     * @return array
     */
    public function getList(array $params, string $orderBy = 'updated_at', string $sort = 'desc'): array
    {
        $lists = $this->model->where(function ($query) use ($params) {
            if (isset($params['search_text']) && $params['search_text'] != '') {
                $query->where('role_name', 'like', '%' . $params['search_text'] . '%');
            }
        })
            ->with('menus:menu_id')
            ->orderBy($orderBy, $sort)
            ->paginate(isset($params['page_size']) ? $params['page_size'] : config('landao.paginate.page_size'));
        $roles = $lists->toArray();
        foreach ($roles['data'] as $key => $role) {
            $roles['data'][$key]['menus'] = Arr::flatten($role['menus']);
        }
        return $roles;
    }

    /**
     * 根据角色ID，获取相应的菜单ids
     * @param $roleIds
     * @return array
     */
    public function getMenuIdsByRoleIds($roleIds): array
    {
        $menuIdsList = RoleHasMenuModel::whereIn('role_id', $roleIds)->pluck('menu_id')->toArray();
        return array_unique($menuIdsList);
    }

    /**
     * 根据角儿ids，获取菜单
     * @param ManageModel $user
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getRoleMenuByRoleIds(ManageModel $user): array
    {
        $roleIds = [];
        foreach ($user->roles as $item) {
            $roleIds[] = $item['role_id'];
        }
        $menuIds = [];
        //获取角色关联权限菜单ids
        if (!(boolean)$user->is_super && count($roleIds) > 0) {
            $menuIds = $this->getMenuIdsByRoleIds($roleIds);
        }
        return $this->app->make(MenuRepo::class)->generatePermission($menuIds);
    }


}
