<?php

namespace App\Http\Requests\Manage;

use JoyceZ\LaravelLib\Validation\BaseRequest;

/**
 * 管理员验证
 * Class ManageRequest
 * @package App\Http\Requests\Manage
 */
class ManageRequest extends BaseRequest
{
    public function getRulesByStore()
    {
        return [
            'username' => 'required|max:60',
            'nickname' => 'required|max:60',
            'realname' => 'required|max:30',
            'phone' => 'mobile|max:11',
            'introduce' => 'max:500',
            'manage_status'=>'required|in:0,1'
        ];
    }

    public function getRulesByUpdate()
    {
        return [
            'username' => 'required|max:60',
            'nickname' => 'required|max:60',
            'realname' => 'required|max:30',
            'phone' => 'mobile|max:11',
            'introduce' => 'max:500',
            'manage_status'=>'required|in:0,1'
        ];
    }


    /**
     * 统一定义验证规则的自定义错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'nickname.required' => '昵称不能为空',
            'nickname.max' => '昵称字数超过了限制',
            'realname.required' => '真实姓名不能为空',
            'realname.max' => '真实姓名字数超过了限制',
//        'phone.required' => '手机号不能为空',
            'phone.mobile' => '请输入11位手机号',
            'phone.max' => '手机号字数超过了限制',
            'introduce.max' => '简介字数超过了限制',
            'manage_status.required' => '缺少账号状态',
            'manage_status.in' => '状态值存在非法值',
        ];
    }
}
