<?php
declare (strict_types=1);

namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\ApiController;
use App\Http\Requests\Manage\RoleRequest;
use App\Services\Repositories\Manage\RoleRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use JoyceZ\LaravelLib\Helpers\FiltersHelper;

/**
 * 角色
 * Class Role
 * @package App\Http\Controllers\Manage\V1
 */
class Role extends ApiController
{
    /**
     * 角色列表
     * @param Request $request
     * @param RoleRepo $roleRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, RoleRepo $roleRepo)
    {
        $params = $request->all();
        $ret = $roleRepo->getList($params, $params['order'] ?? 'created_at', $params['sort'] ?? 'desc');
        $list = $roleRepo->parseDataRows($ret['data']);
        return $this->success([
            'pagination' => [
                'total' => $ret['total'],
                'page_size' => $ret['per_page'],
                'current_page' => $ret['current_page'],
            ],
            'list' => $list
        ]);
    }

    /**
     * 获取全部角色
     * @param RoleRepo $roleRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(RoleRepo $roleRepo)
    {
        $lists = $roleRepo->all(['role_id', 'role_name']);
        return $this->success($roleRepo->parseDataRows($lists->toArray()));
    }

    /**
     * 获取详情
     * @param int $id
     * @param RoleRepo $roleRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(int $id, RoleRepo $roleRepo)
    {
        $role = $roleRepo->getByPkId($id);
        if (!$role) {
            return $this->badSuccessRequest('角色不存在');
        }
        $menuIds = [];
        foreach ($role->menus as $item) {
            $menuIds[] = $item['menu_id'];
        }
        $roleData = $role->toArray();
//        $roleData['menus'] = $menuIds ? (new HashIdsSup())->encodeArray($menuIds) : [];
        return $this->success($roleRepo->parseDataRow($roleData));
    }

    /**
     * 新建角色
     * @param RoleRequest $request
     * @param RoleRepo $roleRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RoleRequest $request, RoleRepo $roleRepo)
    {
        $params = $request->all();
        $data = [
            'role_name' => FiltersHelper::filterXSS(trim($params['role_name'])),
            'role_desc' => FiltersHelper::filterXSS(trim($params['role_desc'])),
        ];
        $roleRepo->transaction();
        try {
            $role = $roleRepo->create($data);
            if ($role) {
                if ($params['menus']) {
                    //对数据进行解密
//                $ids = (new HashIdsSup())->decodeArray($params['menus']);
                    $ids = $params['menus'];
                    $role->menus()->sync(array_filter(array_unique($ids)));
                }
                $roleRepo->commit();
                return $this->successRequest('新增成功');
            }
            $roleRepo->rollBack();
            return $this->badSuccessRequest('新增失败');
        } catch (QueryException $exception) {
            $roleRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 更新角色
     * @param int $roleId
     * @param RoleRequest $request
     * @param RoleRepo $roleRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $roleId, RoleRequest $request, RoleRepo $roleRepo)
    {
        $params = $request->all();

        $role = $roleRepo->getByPkId($roleId);
        if (!$role) {
            return $this->badSuccessRequest('该角色不存在',);
        }
        $role->role_name = FiltersHelper::filterXSS(trim($params['role_name']));
        $role->role_desc = FiltersHelper::filterXSS(trim($params['role_desc']));
        $roleRepo->transaction();
        try {
            if ($role->save()) {
                if ($params['menus']) {
                    //对数据进行解密
                    $ids = $params['menus'];//(new HashIdsSup())->decodeArray($params['menus']);
                    $role->menus()->sync(array_filter(array_unique($ids)));
                }
                $roleRepo->commit();
                return $this->successRequest('更新成功');
            }
            $roleRepo->rollBack();
            return $this->badSuccessRequest('更新失败');
        } catch (QueryException $exception) {
            $roleRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 删除
     * @param int $roleId
     * @param RoleRepo $roleRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $roleId, RoleRepo $roleRepo)
    {
        $role = $roleRepo->getByPkId($roleId);
        if (!$role) {
            return $this->badSuccessRequest('角色不存在');
        }
        $roleRepo->transaction();
        try {
            if ($role->delete()) {
                $role->menus()->detach($roleId);
                $roleRepo->commit();
                return $this->successRequest('删除成功');
            }
            $roleRepo->rollBack();
            return $this->badSuccessRequest('删除失败');
        } catch (QueryException $exception) {
            $roleRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 快捷修改指定表字段值
     * @param Request $request
     * @param RoleRepo $roleRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function modifyFiled(Request $request, RoleRepo $roleRepo)
    {
        $id = intval($request->post('role_id'));
        if ($id <= 0) {
            return $this->badSuccessRequest('缺少必要的参数');
        }
        $fieldName = (string)$request->post('field_name');
        $fieldValue = $request->post('field_value');
        $roleRepo->transaction();
        try {
            if ($roleRepo->updateFieldById($id, $fieldName, $fieldValue)) {
                $roleRepo->commit();
                return $this->successRequest('更新成功');
            }
            $roleRepo->rollBack();
            return $this->badSuccessRequest('更新失败');
        } catch (QueryException $exception) {
            $roleRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }


}
