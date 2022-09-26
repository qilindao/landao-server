<?php

namespace App\Http\Requests\Manage;

use JoyceZ\LaravelLib\Validation\BaseRequest;

/**
 * 权限菜单验证
 * Class MenuRequest
 * @package App\Http\Requests\Manage
 */
class MenuRequest extends BaseRequest
{
    public function getRulesByStore()
    {
        return [
            'menu_title' => 'required|max:150',
            'menu_name' => 'required|max:150',
            'menu_type'=>'required|in:0,1,2'
        ];
    }

    public function getRulesByUpdate()
    {
        return [
            'menu_title' => 'required|max:150',
            'menu_name' => 'required|max:150',
            'menu_type'=>'required|in:0,1,2'
        ];
    }


    /**
     * 统一定义验证规则的自定义错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'menu_title.required' => '请输入菜单名称',
            'menu_title.max' => '菜单名称字数超过了限制',
            'menu_name.required' => '请输入节点路由名',
            'menu_name.max' => '节点路由名字数超过了限制',
            'menu_type.required' => '请选择节点类型',
            'menu_type.in' => '节点类型存在非法值',
        ];
    }
}
