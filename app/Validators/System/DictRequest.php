<?php


namespace App\Validators\System;


use JoyceZ\LaravelLib\Validation\BaseRequest;

class DictRequest extends BaseRequest
{

    /**
     *  定义针对 DictTypeController->store( )的验证规则
     * @return array
     */
    public function getRulesByStore()
    {
        return [
            'label' => 'required',
            'type_id' => 'required|numeric|min:0|not_in:0',
            'is_enable' => 'required|boolean'
        ];
    }

    /**
     *  定义针对 DictTypeController->update( )的验证规则
     * @return array
     */
    public function getRulesByUpdate()
    {
        return [
            'label' => 'required',
            'type_id' => 'required|numeric|min:0|not_in:0',
            'is_enable' => 'required|boolean'
        ];
    }


    /**
     * 统一定义验证规则的自定义错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'label.required' => '请输入名称',
            'type_id.required' => '请选中字典类型',
            'type_id.numeric' => '字典类型值格式有误',
            'type_id.min' => '字典类型值格式有误',
            'type_id.not_in' => '字典类型值格式有误',
            'is_enable.required' => '缺少字典状态',
            'is_enable.boolean' => '字典状态值有误',
        ];
    }
}
