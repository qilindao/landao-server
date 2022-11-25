<?php

declare (strict_types=1);

namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\ApiController;
use App\Http\Requests\Manage\ManageRequest;
use App\Services\Repositories\Manage\ManageRepo;
use App\Services\Repositories\Manage\MenuRepo;
use App\Services\Repositories\System\DictionaryRepo;
use JoyceZ\LaravelLib\Helpers\FiltersHelper;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * 个人信息
 * Class Profile
 * @package App\Http\Controllers\Manage\V1
 */
class Profile extends ApiController
{
    /**
     * 获取个人信息
     * @param ManageRepo $manageRepo
     * @param DictionaryRepo $dictionaryRepo
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function index(ManageRepo $manageRepo, DictionaryRepo $dictionaryRepo)
    {
        $user = JWTAuth::parseToken()->touser();
        $user->roles;
        $user->department;
        $manage = $manageRepo->parseDataRow($user->toArray());
        //字典
        $dictionary = $dictionaryRepo->getAllDictByGroup();
        return $this->success([
            'userInfo' => $manage,
            'dictionary' => $dictionary
        ]);
    }

    /**
     * 更新个人信息
     * @param ManageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ManageRequest $request)
    {
        $params = $request->only(['nickname', 'realname', 'phone', 'introduce']);
        //表单校验

        $user = JWTAuth::parseToken()->touser();
        $user->nickname = FiltersHelper::filterXSS(trim($params['nickname']));
        $user->realname = FiltersHelper::filterXSS(trim($params['realname']));
        $user->phone = trim($params['phone']);
        $user->introduce = FiltersHelper::filterXSS(trim((string)$params['introduce']));
        if ($user->save()) {
            return $this->successRequest('更新个人信息成功');
        }
        return $this->badSuccessRequest('更新个人信息失败');
    }

    /**
     * 获取用户权限菜单和权限按钮
     * @param MenuRepo $menuRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function rules(MenuRepo $menuRepo)
    {
        $user = JWTAuth::parseToken()->touser();
        $roleIds = [];
        foreach ($user->roles as $item) {
            $roleIds[] = $item['role_id'];
        }
        $ret = $menuRepo->generatePermission($roleIds, (boolean)$user->is_super);
        $menus = $menuRepo->parseDataRows($ret['menus']);
        $power = $ret['power'];
        $menuGroup = $ret['menuGroup'];
        return $this->success(compact('menus', 'power', 'menuGroup'));
    }

}
