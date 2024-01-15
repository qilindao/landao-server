<?php
declare(strict_types=1);

namespace App\Services\Repositories\System;

use App\Services\Enums\System\MenuTypeEnum;
use Illuminate\Support\Arr;
use JoyceZ\LaravelLib\Helpers\TreeHelper;
use JoyceZ\LaravelLib\Repositories\BaseRepository;
use App\Services\Models\System\MenuModel;

/**
 * 前端菜单和api权限管理 Repository 接口实现
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Class MenuRepo
 * @package App\Services\Repositories\System;
 */
class MenuRepo extends BaseRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return MenuModel::class;
    }

    /**
     * 需要展示的字段
     * @var array
     */
    const MENU_COLUMNS = [
        'menu_id',
        'parent_id',
        'name',
        'title',
        'icon',
        'type',
        'redirect',
        'path',
        'component',
        'auth_code',
        'order_no',
        'keep_alive',
        'hidden'
    ];

    /**
     * 获取菜单和权限
     * @param string $module
     * @return array
     */
    public function lists($module = 'ALL'): array
    {
        $query = $this->model;
        if (trim($module) != 'ALL') {
            $query->where('module', trim($module));
        }
        $menuList = $query->orderBy('order_no', 'ASC')->get(self::MENU_COLUMNS)->toArray();
        return $this->buildMenu($menuList);
    }

    /**
     * 组装前端需要的菜单数据格式
     * @param array $menuList
     * @return array
     */
    private function buildMenu(array $menuList): array
    {
        $list = [];
        foreach ($menuList as $item) {
            $meta = [
                'title' => $item['title'],
                'icon' => $item['icon'],
                'keepAlive' => $item['keep_alive'],
                'orderNo' => $item['order_no'],
                'hidden' => $item['hidden'],
            ];
            //添加新的键值，移除不需要的键值
            $list[] = Arr::except(Arr::add($item, 'meta', $meta), ['title', 'icon', 'keep_alive', 'order_no', 'hidden']);
        }
        return $list;
    }

    /**
     * 获取角色对应 菜单和权限
     * @param array $menuIdsList 菜单IDs
     * @return array
     */
    public function generatePermission(array $menuIdsList = []): array
    {
        $menuIds = array_unique($menuIdsList);
        $menuListRet = $this->model->where(function ($query) use ($menuIds) {
            if (count($menuIds) > 0) {
                $query->whereIn('menu_id', $menuIds);
            }
        })->orderBy('order_no', 'ASC')->get(self::MENU_COLUMNS)->toArray();
        $menus = $power = [];
        $menuList = $this->buildMenu($menuListRet);

        foreach ($menuList as $item) {
            //目录和菜单
            if (in_array($item['type'], [MenuTypeEnum::MENU_TYPE_CATALOG, MenuTypeEnum::MENU_TYPE_MENU])) {
                $menus[] = $item;
                continue;
            } elseif ($item['type'] == MenuTypeEnum::MENU_TYPE_BUTTON) {//按钮权限
                $power[] = $item['auth_code'];
                continue;
            }
        }
        //将菜单打成树形结构
        $menus = TreeHelper::listToTree($menus, 0, 'menu_id', 'parent_id');
        return compact('menus', 'power');
    }
}
