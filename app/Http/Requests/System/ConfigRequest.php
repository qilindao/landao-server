<?php


namespace App\Http\Requests\System;


use App\Services\Enums\System\ConfigTypeEnum;
use Illuminate\Validation\Rule;
use JoyceZ\LaravelLib\Validation\BaseRequest;

/**
 * 添加配置项验证
 * Class ConfigRequest
 * @package App\Http\Requests\System
 */
class ConfigRequest extends BaseRequest
{
    /**
     *  定义针对 DictTypeController->store( )的验证规则
     * @return array
     */
    public function getRulesByStore()
    {
        return [
            'name' => 'required|regex:/^[a-zA-Z][a-zA-Z0-9_]*$/',
            'group' => 'required',
            'title' => 'required',
            'type' => ['required', Rule::in(ConfigTypeEnum::getKeys())],
        ];
    }


    /**
     * 统一定义验证规则的自定义错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '请输入变量名',
            'name.regex' => '变量名由英文、数字和下划线组成，且开头必须英文',
            'group.required' => '请选择变量分组',
            'title.required' => '请填写变量标题',
            'type.required' => '请选择变量类型',
            'type.in' => '变量类型值不在指定类型当中',
        ];
    }
}
