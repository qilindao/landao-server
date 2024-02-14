<?php

namespace App\Http\Requests\Manage;

use JoyceZ\LaravelLib\Validation\BaseRequest;

/**
 * 角色表单验证
 * Class RoleRequest
 * @package App\Http\Requests\Manage
 */
class RoleRequest extends BaseRequest
{
    public function getRulesByStore()
    {
        return [
            'role_name' => 'required|max:150',
            'role_desc' => 'max:250',
//            'menus' => 'required|array|min:1'
        ];
    }

    public function getRulesByUpdate()
    {
        return [
            'role_name' => 'required|max:150',
            'role_desc' => 'max:250',
//            'menus' => 'required|array|min:1'
        ];
    }

    public function getRulesByUpdateRoleAuth(){
        return [
            'menus' => 'required|array|min:1'
        ];
    }


    /**
     * 统一定义验证规则的自定义错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'role_name.required' => '请输入角色名',
            'role_name.max' => '角色名字数超过限制',
            'role_desc.max' => '角色备注字数超过了限制',
            'menus.required' => '请选中功能权限',
            'menus.array' => '功能权限格式错误',
            'menus.min' => '至少选中一个功能权限'
        ];
    }
}
