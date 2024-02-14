<?php
declare(strict_types=1);

namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\ApiController;
use App\Http\Requests\Manage\DeptRequest;
use App\Services\Repositories\Manage\DepartmentRepo;
use App\Services\Repositories\Manage\RoleRepo;
use App\Services\Repositories\System\MenuRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use JoyceZ\LaravelLib\Helpers\FiltersHelper;

/**
 *
 * 部门管理
 *
 * @author joyecZhang <zhangwei762@163.com>
 *
 * Class Dept
 * @package App\Http\Controllers\Manage\V1
 */
class Dept extends ApiController
{
    /**
     * 列表
     * @param Request $request
     * @param DepartmentRepo $departmentRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, DepartmentRepo $departmentRepo)
    {
        $params = $request->all();
        $ret = $departmentRepo->getList($params);
        return $this->success($departmentRepo->parseDataRows($ret));
    }

    /**
     * 获取详情
     * @param int $id
     * @param DepartmentRepo $departmentRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(int $id, DepartmentRepo $departmentRepo)
    {
        $dept = $departmentRepo->getByPkId($id);
        if (!$dept) {
            return $this->badSuccessRequest('部门不存在');
        }
        return $this->success($departmentRepo->parseDataRow($dept->toArray()));
    }

    /**
     * 新建
     * @param DeptRequest $request
     * @param DepartmentRepo $departmentRepo
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \JoyceZ\LaravelLib\Exceptions\RepositoryException
     */
    public function store(DeptRequest $request, DepartmentRepo $departmentRepo)
    {
        $params = $request->all();
        $data = [
            'dept_name' => FiltersHelper::filterXSS(trim($params['dept_name'])),
            'dept_desc' => trim($params['dept_desc']) ? FiltersHelper::filterXSS(trim($params['dept_desc'])) : '',
            'dept_order' => intval($params['dept_order']),
            'parent_id' => (int)$params['parent_id']
        ];
        $departmentRepo->transaction();
        try {
            if ($departmentRepo->create($data)) {
                $departmentRepo->commit();
                return $this->successRequest('新增成功');
            }
            $departmentRepo->rollBack();
            return $this->badSuccessRequest('新增失败');
        } catch (QueryException $exception) {
            $departmentRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 更新
     * @param int $deptId
     * @param DeptRequest $request
     * @param DepartmentRepo $departmentRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $deptId, DeptRequest $request, DepartmentRepo $departmentRepo)
    {
        $params = $request->all();
        $dept = $departmentRepo->getByPkId($deptId);
        if (!$dept) {
            return $this->badSuccessRequest('部门信息不存在');
        }
        $dept->dept_name = FiltersHelper::filterXSS(trim($params['dept_name']));
        $dept->dept_desc = trim($params['dept_desc']) ? FiltersHelper::filterXSS(trim($params['dept_desc'])) : '';
        $dept->dept_order = intval($params['dept_order']);
        $dept->parent_id = (int)$params['parent_id'];

        $departmentRepo->transaction();
        try {
            if ($dept->save()) {
                $departmentRepo->commit();
                return $this->successRequest('更新成功');
            }
            $departmentRepo->rollBack();
            return $this->badSuccessRequest('更新失败');
        } catch (QueryException $exception) {
            $departmentRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 删除
     * @param int $deptId
     * @param DepartmentRepo $departmentRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $deptId, DepartmentRepo $departmentRepo)
    {
        $dept = $departmentRepo->getByPkId($deptId);
        if (!$dept) {
            return $this->badSuccessRequest('部门不存在');
        }
        $departmentRepo->transaction();
        try {
            if ($dept->delete()) {
                $departmentRepo->commit();
                return $this->successRequest('删除成功');
            }
            $departmentRepo->rollBack();
            return $this->badSuccessRequest('删除失败');
        } catch (QueryException $exception) {
            $departmentRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 快捷修改指定表字段值
     * @param Request $request
     * @param int $deptId
     * @param DepartmentRepo $deptRepo
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \JoyceZ\LaravelLib\Exceptions\RepositoryException
     */
    public function modifyFiled(Request $request, int $deptId, DepartmentRepo $deptRepo)
    {
        if ($deptId <= 0) {
            return $this->badSuccessRequest('缺少必要的参数');
        }
        $fieldName = (string)$request->post('field_name');
        $fieldValue = $request->post('field_value');
        $deptRepo->transaction();
        try {
            if ($deptRepo->updateFieldById($deptId, $fieldName, $fieldValue)) {
                $deptRepo->commit();
                return $this->successRequest('更新成功');
            }
            $deptRepo->rollBack();
            return $this->badSuccessRequest('更新失败');
        } catch (QueryException $exception) {
            $deptRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }
}
