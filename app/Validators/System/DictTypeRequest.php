<?php


namespace App\Validators\System;


use JoyceZ\LaravelLib\Validation\BaseRequest;

/**
 * Class DictTypeRequest
 * @package App\Validators\System
 */
class DictTypeRequest extends BaseRequest
{

    /**
     *  定义针对 DictTypeController->store( )的验证规则
     * @return array
     */
    public function getRulesByStore()
    {
        return [
            'type_name' => 'required',
            'type_key' => 'required'
        ];
    }

    /**
     *  定义针对 DictTypeController->update( )的验证规则
     * @return array
     */
    public function getRulesByUpdate()
    {
        return [
            'type_name' => 'required',
            'type_key' => 'required'
        ];
    }


    /**
     * 统一定义验证规则的自定义错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'type_name.required' => '请填写标识名',
            'type_key.required' => '请填写标识key',
        ];
    }


}
