<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;
use JoyceZ\LaravelLib\Traits\ApiResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTRoleAuth extends BaseMiddleware
{
    use ApiResponse;

    /**
     * JWT 检测当前登录的平台
     * @param $request
     * @param Closure $next
     * @param null $role
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws JWTException
     */
    public function handle($request, Closure $next, $role = null)
    {
        try {
            $tokenRole = $this->auth->parseToken()->getClaim('role');
            if ($tokenRole != $role) {
                return $this->unAuthorized('token 授权失败');
            }
            $user = Auth::guard($tokenRole)->user();
            if (!$user) {  //获取到用户数据，并赋值给$user
                return $this->badSuccessRequest('用户不存在');
            }
            return $next($request);
        } catch (TokenExpiredException $e) {
            return $this->unAuthorized('token 过期');
        } catch (TokenInvalidException $e) {
            return $this->unAuthorized('token 无效');
        } catch (JWTException $e) {
            return $this->unAuthorized('缺少 token');
        } catch (TokenBlacklistedException $e) {
            return $this->unAuthorized('缺少 token');
        }
    }
}
