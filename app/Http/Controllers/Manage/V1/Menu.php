<?php
declare (strict_types=1);

namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\ApiController;
use App\Http\Requests\Manage\MenuRequest;
use App\Services\Repositories\Manage\MenuRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * 菜单
 *
 * @author joyecZhang <zhangwei762@163.com>
 * Class Menu
 * @package App\Http\Controllers\Manage\V1
 */
class Menu extends ApiController
{
    public function index(Request $request, MenuRepo $menuRepo)
    {
        $menuList = $menuRepo->getAllList($request->all());
        return $this->success($menuRepo->parseDataRows($menuList));
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
            if (isset($route->action['as'])) {
                if (Str::is('manage.*', $route->action['as'])) {
                    $arrRoute[] = $route->action['as'];
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
     */
    public function store(MenuRequest $request, MenuRepo $menuRepo)
    {
        $params = $request->all();
        $params['parent_id'] = trim((string)$params['parent_id']) == '' ? 0 : $params['parent_id'];

        $menuRepo->transaction();
        try {
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
     */
    public function update(int $menuId, MenuRequest $request, MenuRepo $menuRepo)
    {
        $menuRepo->transaction();
        try {
            if ($menuRepo->updateById($request->all(), $menuId)) {
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
}
