<?php

namespace App\Http\Requests\Passport;

use JoyceZ\LaravelLib\Validation\BaseRequest;

/**
 * 运营平台登录验证
 * Class ManageLoginRequest
 * @package App\Http\Requests\Passport
 */
class ManageLoginRequest extends BaseRequest
{
    /**
     * 登录验证
     * @return array
     */
    public function getRulesByLogin()
    {
        return [
            'username' => 'required',
            'password' => 'required|min:8',
            'captcha' => 'required|size:4',
            'captcha_uniqid'=>'required'
        ];
    }


    /**
     * 统一定义验证规则的自定义错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'username.required' => '账号不能为空',
            'password.required' => '密码不能为空',
            'password.min' => '密码最少要输入8个字符',
            'captcha.required' => '验证码不能为空',
            'captcha.size' => '验证码位数错误',
            'captcha_uniqid.required' => '非法操作验证码',
        ];
    }
}
