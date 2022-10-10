<?php

declare (strict_types=1);

namespace App\Http\Controllers\Manage\V1;


use App\Events\PassportManageLoginAfterEvent;
use App\Events\PassportManageRefreshTokenEvent;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Passport\ManageLoginRequest;
use App\Services\Repositories\Manage\ManageRepo;
use App\Support\CryptoJsSup;
use Illuminate\Support\Arr;
use JoyceZ\LaravelLib\Contracts\Captcha as CaptchaInterface;
use JoyceZ\LaravelLib\Security\AopPassword;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTFactory;

/**
 * 登录相关
 * Class Passport
 * @package App\Http\Controllers\Manage\V1
 */
class Passport extends ApiController
{

    /**
     * 获取图形验证码
     * @param CaptchaInterface $captchaRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function captcha(CaptchaInterface $captchaRepo)
    {
        $captcha = $captchaRepo->makeCode()->get();
        $captchaImg = Arr::get($captcha, 'image', '');
        $captchaUniqid = Arr::get($captcha, 'uniq', '');
        return $this->success([
            'captcha' => $captchaImg,
            config('landao.passport.check_captcha_cache_key') => $captchaUniqid
        ]);
    }

    /**
     * 管理员登陆
     * @param ManageLoginRequest $request
     * @param CaptchaInterface $captchaRepo
     * @param ManageRepo $manageRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(ManageLoginRequest $request, CaptchaInterface $captchaRepo, ManageRepo $manageRepo)
    {
        $params = $request->all();

        //图形验证码校验
        $captchaUniq = $params[config('landao.passport.check_captcha_cache_key')];
        if (!$captchaRepo->check($params['captcha'], $captchaUniq)) {
            return $this->badSuccessRequest('验证码错误');
        }
        $manage = $manageRepo->getInfoByUsername(trim($params['username']));
        if (!$manage) {
            return $this->badSuccessRequest('账号不存在');
        }
        $manageInfo = $manage->makeVisible(['password', 'pwd_salt'])->toArray();
        //将前端加密的密码进行解密
        $password = (new CryptoJsSup($captchaUniq))->decrypt($params['password']);
        //密码验证
        $pwdFlag = (new AopPassword())
            ->withSalt()
            ->check($manageInfo['password'], (string)$password, (string)$manageInfo['pwd_salt']);
        if (!$pwdFlag) {
            return $this->badSuccessRequest('账号密码错误');
        }
        if (intval($manageInfo['manage_status']) != 1) {
            return $this->badSuccessRequest('用户已被禁用');
        }
//        $token = JWTAuth::fromUser($manage);
        $token = $this->withAuthGuard('admin')->login($manage);
        $jwt = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTFactory::getTTL() * 60
        ];
        event(new PassportManageLoginAfterEvent($manage, $jwt));
        return $this->success($jwt, '登录成功');
    }

    /**
     * 刷新令牌
     * https://www.yangpanyao.com/archives/81.html
     * https://zhuanlan.zhihu.com/p/80352766
     * https://github.com/tymondesigns/jwt-auth/issues?q=refresh
     * https://jwt-auth.readthedocs.io/en/develop/search.html?q=expires_in
     * @param ManageRepo $manageRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(ManageRepo $manageRepo)
    {
        try {
//            $user = JWTAuth::parseToken()->touser();
            $manage = $this->getAuthUser('admin');
//            $manage = $manageRepo->getByPkId($user->manage_id);
            if (!$manage) {
                return $this->badSuccessRequest('账号不存在');
            }
            if (intval($manage->manage_status) != 1) {
                return $this->badSuccessRequest('用户已被禁用');
            }
//            $token = JWTAuth::parseToken()->refresh();
            //TODO:refresh 中不要再加任何参数，否则 getJWTCustomClaims 无效，刷新的令牌不会加上，model 中自定义的 role 参数
            $token = $this->withAuthGuard('admin')->refresh();
            $jwt = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTFactory::getTTL() * 60
            ];
            event(new PassportManageRefreshTokenEvent($manage, $jwt));
            return $this->success($jwt, '刷新成功');
        } catch (TokenInvalidException $e) {
            return $this->unAuthorized('无效token');
        } catch (JWTException $e) {
            return $this->unAuthorized('无法刷新令牌');
        }
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
//            $user = JWTAuth::parseToken()->touser();
            $user = $this->getAuthUser('admin');
            event(new PassportManageRefreshTokenEvent($user, []));
//            JWTAuth::parseToken()->invalidate();//退出
            //使token无效
            $this->withAuthGuard('admin')->invalidate(true);
            return $this->successRequest('登出成功');
        } catch (TokenInvalidException $e) {
            return $this->unAuthorized('无效token');
        } catch (TokenBlacklistedException $e) {
            return $this->unAuthorized('无效token');
        }
    }


}
