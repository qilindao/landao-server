<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use JoyceZ\LaravelLib\Traits\ApiResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\Repositories\Manage\RoleRepo;

/**
 * 后端权限验证
 *
 * @author joyecZhang <zhangwei762@163.com>
 *
 * Class AdminPermission
 * @package App\Http\Middleware
 */
class AdminPermission
{
    use ApiResponse;

    public function handle($request, Closure $next)
    {
        $user = JWTAuth::parseToken()->touser();
        //不是超级管理员，需进行权限验证
        if (intval($user->is_super) <= 0) {
            $result = app(RoleRepo::class)->getRoleMenuByRoleIds($user);
            $routeAsName = Route::currentRouteName();
            //判断当前路由别名是否存在访问权限中
            if (!in_array($routeAsName, $result['power'])) {
                return $this->forbidden('无访问权限');
            }
        }
        return $next($request);
    }

}
