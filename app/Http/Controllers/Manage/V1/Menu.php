<?php
declare (strict_types=1);

namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\ApiController;
use App\Http\Requests\System\MenuRequest;
use App\Services\Enums\System\MenuTypeEnum;
use App\Services\Repositories\System\MenuRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

/**
 * 菜单
 *
 * @author joyecZhang <zhangwei762@163.com>
 * Class Menu
 * @package App\Http\Controllers\Manage\V1
 */
class Menu extends ApiController
{

    /**
     * 菜单列表
     * @param MenuRepo $menuRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(MenuRepo $menuRepo)
    {
        $menuList = $menuRepo->lists();
        return $this->success($menuList);
    }

    /**
     * 获取可操作权限
     * @return \Illuminate\Http\JsonResponse
     */
    public function power()
    {
        $routes = app()->routes->getRoutes();
        $arrRoute = [];
        foreach ($routes as $route) {
            if (isset($route->action['as']) && isset($route->action['name'])) {
                if ($route->action['name'] == 'manage.') {
                    $arrRoute[] = $route->action['name'] . $route->action['as'];
                }
            }
        }
        return $this->success($arrRoute);
    }

    /**
     * 获取详情
     * @param int $id
     * @param MenuRepo $menuRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(int $id, MenuRepo $menuRepo)
    {
        $menu = $menuRepo->getByPkId($id);
        if (!$menu) {
            return $this->badSuccessRequest('菜单不存在');
        }
        return $this->success($menuRepo->parseDataRow($menu->toArray()));
    }

    /**
     * 新增菜单
     * @param MenuRequest $request
     * @param MenuRepo $menuRepo
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \JoyceZ\LaravelLib\Exceptions\RepositoryException
     */
    public function store(MenuRequest $request, MenuRepo $menuRepo)
    {
        $params = $request->all();
        $params['parent_id'] = trim((string)$params['parent_id']) == '' ? 0 : $params['parent_id'];
        $menu = $menuRepo->existsWhere(['name' => $params['name']]);
        if ($menu) {
            return $this->badSuccessRequest('节点路由名已存在');
        }
        $menuRepo->transaction();
        try {
            $params['component'] = $params['type'] == MenuTypeEnum::MENU_TYPE_BUTTON ? "" : $params['component'];
            $params['keep_alive'] = $params['type'] == MenuTypeEnum::MENU_TYPE_BUTTON ? false : $params['keep_alive'];
            if ($menuRepo->create($params)) {
                $menuRepo->commit();
                return $this->successRequest('新增成功');
            }
            $menuRepo->rollBack();
            return $this->badSuccessRequest('新增失败');
        } catch (QueryException $exception) {
            $menuRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 修改菜单
     * @param int $menuId 菜单id
     * @param MenuRequest $request
     * @param MenuRepo $menuRepo
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \JoyceZ\LaravelLib\Exceptions\RepositoryException
     */
    public function update(int $menuId, MenuRequest $request, MenuRepo $menuRepo)
    {
        $menuRepo->transaction();
        try {
            $params = $request->all();
            $params['component'] = $params['type'] == MenuTypeEnum::MENU_TYPE_BUTTON ? "" : $params['component'];
            $params['keep_alive'] = $params['type'] == MenuTypeEnum::MENU_TYPE_BUTTON ? false : $params['keep_alive'];
            if ($menuRepo->updateById($params, $menuId)) {
                $menuRepo->commit();
                return $this->successRequest('更新成功');
            }
            $menuRepo->rollBack();
            return $this->badSuccessRequest('更新失败');
        } catch (QueryException $exception) {
            $menuRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 删除
     * @param int $menuId
     * @param MenuRepo $menuRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $menuId, MenuRepo $menuRepo)
    {
        $menu = $menuRepo->getByPkId($menuId);
        if (!$menu) {
            return $this->badSuccessRequest('菜单不存在');
        }
        $menuRepo->transaction();
        try {
            if ($menu->delete()) {
                $menu->roles()->detach($menuId);
                $menuRepo->commit();
                return $this->successRequest('删除成功');
            }
            $menuRepo->rollBack();
            return $this->badSuccessRequest('更新失败');
        } catch (QueryException $exception) {
            $menuRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 快捷修改指定表字段值
     * @param Request $request
     * @param int $menuId
     * @param MenuRepo $menuRepo
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \JoyceZ\LaravelLib\Exceptions\RepositoryException
     */
    public function modifyFiled(Request $request,int $menuId, MenuRepo $menuRepo)
    {
        if ($menuId <= 0) {
            return $this->badSuccessRequest('缺少必要的参数');
        }
        $fieldName = (string)$request->post('field_name');
        $fieldValue = $request->post('field_value');
        $menuRepo->transaction();
        try {
            if ($menuRepo->updateFieldById($menuId, $fieldName, $fieldValue)) {
                $menuRepo->commit();
                return $this->successRequest('更新成功');
            }
            $menuRepo->rollBack();
            return $this->badSuccessRequest('更新失败');
        } catch (QueryException $exception) {
            $menuRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }
}
