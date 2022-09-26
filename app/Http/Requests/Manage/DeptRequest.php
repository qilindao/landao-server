<?php

namespace App\Http\Requests\Manage;

use JoyceZ\LaravelLib\Validation\BaseRequest;

/**
 * 部门
 * Class DeptRequest
 * @package App\Http\Requests\Manage
 */
class DeptRequest extends BaseRequest
{
    public function getRulesByStore()
    {
        return [
            'dept_name' => 'required|max:150',
            'dept_desc' => 'max:250',
            'parent_id' => 'required'
        ];
    }

    public function getRulesByUpdate()
    {
        return [
            'dept_name' => 'required|max:150',
            'dept_desc' => 'max:250',
            'parent_id' => 'required'
        ];
    }

    /**
     * 统一定义验证规则的自定义错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'dept_name.required' => '请输入部门名',
            'dept_name.max' => '部门名字数超过限制',
            'dept_desc.max' => '部门备注字数超过了限制',
            'parent_id.required' => '请选择上级部门',
        ];
    }
}
