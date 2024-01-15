<?php

namespace App\Http\Requests\Manage;

use App\Services\Enums\System\MenuTypeEnum;
use Illuminate\Validation\Rule;
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
            'title' => 'required|max:150',
            'name' => 'required|max:150',
            'type' => ['required', Rule::in(MenuTypeEnum::getKeys())],
            'path' => 'required_if:type,1',//type = 1 时，菜单路由的路径
            'component' => Rule::requiredIf(function () {//目录和菜单时，必须填写 组件名
                return in_array(intval(request()->input('type')), [MenuTypeEnum::MENU_TYPE_CATALOG, MenuTypeEnum::MENU_TYPE_MENU]);
            }),
            'auth_code' => 'required_if:type,2',//type = 2 时，必须输入权限编码
        ];
    }

    public function getRulesByUpdate()
    {
        return [
            'title' => 'required|max:150',
            'name' => 'required|max:150',
            'type' => ['required', Rule::in(MenuTypeEnum::getKeys())],
            'path' => 'required_if:type,1',//type = 1 时，菜单路由的路径
            'component' => Rule::requiredIf(function () {//目录和菜单时，必须填写 组件名
                return in_array(intval(request()->input('type')), [MenuTypeEnum::MENU_TYPE_CATALOG, MenuTypeEnum::MENU_TYPE_MENU]);
            }),
            'auth_code' => 'required_if:type,2',//type = 2 时，必须输入权限编码
        ];
    }


    /**
     * 统一定义验证规则的自定义错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => '请输入菜单名称',
            'title.max' => '菜单名称字数超过了限制',
            'name.required' => '请输入节点路由名',
            'name.max' => '节点路由名字数超过了限制',
            'type.required' => '请选择节点类型',
            'type.in' => '节点类型存在非法值',
            'path.required_if' => '请填写菜单路径',
            'component.required_if' => '请填写组件视图路径',
            'auth_code.required_if' => '请选择权限编码',
        ];
    }
}
