<?php
declare(strict_types=1);

namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\ApiController;
use App\Http\Requests\Manage\ManageRequest;
use App\Services\Enums\Common\YesOrNoEnum;
use App\Services\Repositories\Manage\ManageRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JoyceZ\LaravelLib\Helpers\FiltersHelper;
use JoyceZ\LaravelLib\Helpers\StrHelper;
use JoyceZ\LaravelLib\Security\AopPassword;

/**
 * 后台用户管理
 *
 * @author joyecZhang <zhangwei762@163.com>
 *
 * Class Manage
 * @package App\Http\Controllers\Manage\V1
 */
class Manage extends ApiController
{
    /**
     * 列表
     * @param Request $request
     * @param ManageRepo $manageRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ManageRepo $manageRepo)
    {
        $params = $request->all();
        $ret = $manageRepo->getList($params, $params['order'] ?? 'reg_date', $params['sort'] ?? 'desc');
        $list = $manageRepo->parseDataRows($ret['data']);
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
     * 用户详情
     * @param int $manageId
     * @param ManageRepo $manageRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(int $manageId, ManageRepo $manageRepo)
    {
        $manage = $manageRepo->getByPkId($manageId);
        if (!$manage) {
            return $this->badSuccessRequest('用户不存在');
        }
        $manage->roles;
        $manage->department;
        return $this->success($manageRepo->parseDataRow($manage->toArray()));
    }


    /**
     * 创建管理员
     * @param ManageRequest $request
     * @param ManageRepo $manageRepo
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \JoyceZ\LaravelLib\Exceptions\RepositoryException
     */
    public function store(ManageRequest $request, ManageRepo $manageRepo)
    {
        $params = $request->all();
        $username = FiltersHelper::filterXSS(trim($params['username']));
        //查看是否重名
        $manage = $manageRepo->where(['username' => $username])->first(['manage_id']);
        if ($manage) {
            return $this->badSuccessRequest('【' . $username . '】用户名已被使用');
        }
        $salt = Str::random(6);
        $data = [
            'username' => $username,
            'realname' => FiltersHelper::filterXSS(trim($params['realname'])),
            'nickname' => FiltersHelper::filterXSS(trim($params['nickname'])),
            'dept_id' => intval($params['dept_id']),
            'pwd_salt' => $salt,
            'password' => (new AopPassword())->withSalt()->encrypt('123qwe@ASD', $salt),
            'is_super' => YesOrNoEnum::COMMON_NO,
            'reg_ip' => StrHelper::ip2long(),
            'manage_status' => intval($params['manage_status']),
            'phone' => FiltersHelper::filterXSS(trim($params['phone'])),
            'introduce' => FiltersHelper::filterXSS(trim($params['introduce']))
        ];

        $manageRepo->transaction();
        try {
            $manage = $manageRepo->create($data);
            if ($manage) {
                if ($params['roles']) {
                    //对数据进行解密
                    $ids = $params['roles'];
                    $manage->roles()->sync(array_filter(array_unique($ids)));
                }
                $manageRepo->commit();
                return $this->successRequest('新增成功');
            }
            $manageRepo->rollBack();
            return $this->badSuccessRequest('新增失败');
        } catch (QueryException $exception) {
            $manageRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 更新成功
     * @param int $manageId
     * @param ManageRequest $request
     * @param ManageRepo $manageRepo
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \JoyceZ\LaravelLib\Exceptions\RepositoryException
     */
    public function update(int $manageId, ManageRequest $request, ManageRepo $manageRepo)
    {
        $params = $request->all();
        $manage = $manageRepo->getByPkId($manageId);
        if (!$manage) {
            return $this->badSuccessRequest('该账号不存在');
        }
        $username = FiltersHelper::filterXSS(trim($params['username']));
        //查看是否重名
        $manageUser = $manageRepo->where([['username', '=', $username], ['manage_id', '<>', $manageId]])->first(['manage_id']);
        if ($manageUser) {
            return $this->badSuccessRequest('【' . $username . '】用户名已被使用');
        }
        $manage->username = $username;
        $manage->realname = FiltersHelper::filterXSS(trim($params['realname']));
        $manage->nickname = FiltersHelper::filterXSS(trim($params['nickname']));
        $manage->dept_id = intval($params['dept_id']);
        $manage->manage_status = intval($params['manage_status']);
        $manage->phone = FiltersHelper::filterXSS(trim($params['phone']));
        $manage->introduce = FiltersHelper::filterXSS(trim($params['introduce']));

        $manageRepo->transaction();
        try {
            if ($manage->save()) {
                if ($params['roles']) {
                    //对数据进行解密
                    $ids = $params['roles'];
                    $manage->roles()->sync(array_filter(array_unique($ids)));
                }
                $manageRepo->commit();
                return $this->successRequest('更新成功');
            }
            $manageRepo->rollBack();
            return $this->badSuccessRequest('更新失败');
        } catch (QueryException $exception) {
            $manageRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 删除
     * @param int $manageId
     * @param ManageRepo $manageRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $manageId, ManageRepo $manageRepo)
    {
        $manage = $manageRepo->getByPkId($manageId);
        if (!$manage) {
            return $this->badSuccessRequest('用户不存在');
        }
        $manageRepo->transaction();
        try {
            if ($manage->delete()) {
                $manage->roles()->detach($manageId);
                $manageRepo->commit();
                return $this->successRequest('删除成功');
            }
            $manageRepo->rollBack();
            return $this->badSuccessRequest('更新失败');
        } catch (QueryException $exception) {
            $manageRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

}
